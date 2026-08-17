<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>FilesILove</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body>
<div x-data="converterApp()">
    <header class="bg-white pt-8 pb-8 px-6 max-w-full mx-auto flex italic font-sans items-center justify-start gap-8">
    
        <h1 class="flex items-center gap-4 text-slate-900 font-black text-5xl tracking-tight px-100">

            <span class="inline-flex items-center justify-center w-14 h-14 rounded-full bg-red-600 text-white shadow-md">
            <svg xmlns="http://w3.org" viewBox="0 0 24 24" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="w-7 h-7 stroke-current fill-none">
                <path d="M4 6.835V4a2 2 0 0 1 2-2h8a2.4 2.4 0 0 1 1.706.706l3.588 3.588A2.4 2.4 0 0 1 20 8v12a2 2 0 0 1-2 2h-.343"/>
                <path d="M14 2v5a1 1 0 0 0 1 1h5"/>
                <path d="M2 19a2 2 0 0 1 4 0v1a2 2 0 0 1-4 0v-4a6 6 0 0 1 12 0v4a2 2 0 0 1-4 0v-1a2 2 0 0 1 4 0"/>
            </svg>
            </span>      
            
            <span class="flex items-center">
            FilesIL
            <svg xmlns="http://w3.org" viewBox="0 0 24 24" stroke-width="1.5" class="w-10 h-10 fill-red-600 stroke-red-600 mx-1">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" />
            </svg>
            ve
            </span>
        </h1>

        <span class="flex items-center gap-1.5 text-base text-gray-500 font-bold mt-3 px-175">
            Made with love 
            <svg xmlns="http://w3.org" viewBox="0 0 24 24" class="w-4 h-4 fill-red-600 stroke-red-600"><path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" /></svg>
        </span>
    </header>

    <hr class="mt-0 mb-8 border-t border-slate-200/60 max-w-full mx-auto px-6" />


    <input type="file" @change="handleFileSelect($event)">
            <select x-show="availableFormats.length > 0" x-model="targetFormat">
                <option value=""> Select target format</option>
                <template x-for="format in availableFormats" :key="format">
                    <option :value="format" x-text="format"></option> 
                </template>
            </select>    
                <button @click="submitConversion()" x-show="targetFormat">Convert</button>
                <button x-show="status === 'completed'" @click="downloadFile()">Download</button>
                <p x-show="status === 'failed'" x-text="errorMessage"></p>
            <p x-text="status"></p>
</div>

    <script>
        function converterApp(){
            return{
                status: 'idle',
                selectedFile: null,
                sourceFormat: null,
                targetFormat: '',
                availableFormats: [],
                conversionType: null,
                pollInterval: null,
                errorMessage: '',

               async handleFileSelect(event){

                    const file = event.target.files[0];
                    if(!file) return;
   
                    this.selectedFile = file;
                    this.sourceFormat = file.name.split('.').pop().toLowerCase();
                    this.targetFormat = ''

                    await this.loadAvailableFormats();

                    console.log('File Selected:', file.name, 'Format:', this.sourceFormat)


                },

                async loadAvailableFormats() {

                    const response = await fetch('/api/formats')
                    const config = await response.json();


                    for(const [type, data] of Object.entries(config)){ 

                        if(data.formats[this.sourceFormat]){

                            this.conversionType = type;
                            this.availableFormats = data.formats[this.sourceFormat];

                            return;
                        }
                    }

                    this.conversionType = null;
                    this.availableFormats = [];
                },

                async submitConversion(){

                    this.status = 'Uploading';

                    const formData = new FormData();
                    formData.append('file', this.selectedFile);
                    formData.append('target_format', this.targetFormat);

                    const endpoint = this.conversionType === 'document'
                        ? '/api/convert'
                        :'/api/convert-media';

                    const response = await fetch(endpoint,{

                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                        },
                        body: formData,
                    });

                    const data = await response.json();

                    
                    if(!response.ok){
                        this.status = 'failed';
                        console.error('Upload Data:', data);
                        return;
                    }

                    this.conversionId = data.id ?? data.media_id;
                    this.status = 'Processing';
                    this.startPolling();
                
                },

                async startPolling() {

                    this.pollInterval = setInterval(async () => {

                        const response = await fetch(`/api/status/${this.conversionType}/${this.conversionId}`, {

                            headers: {
                                'Accept': 'application/json',
                            },
                        });

                        const data = await response.json();

                        if(data.status === 'completed'){
                            clearInterval(this.pollInterval);
                            this.status = 'completed';
                        }else{

                            if(data.status == 'failed'){
                                clearInterval(this.pollInterval);
                                this.status = 'Failed';
                                this.errorMessage = data.error_message ?? 'Conversion Failed';
                            }
                        }
                    }, 2000);
                },

                downloadFile() {

                    window.location.href = `/api/download/${this.conversionType}/${this.conversionId}`
                },
               
            }
        }
    </script>
</body>

</html>