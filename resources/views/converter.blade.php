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
    <br> <br> <br> <br>


    <div class="text-center text-red-800 font-bold text-xl mb-4 bg-rose-100 border border-red-200 rounded-4xl py-2 px-4 max-w-2xs mx-auto mb-15">
       <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 inline-block mr-0.5 mb-1">
        <path stroke-linecap="round" stroke-linejoin="round" d="m3.75 13.5 10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75Z" />
    </svg> Runs in your browser
    </div>

    <div class="text-center mb-10">
        <h2 class="text-7xl font-bold text-black italic">Convert your files.</h2>
        <h3 class="text-5xl font-bold text-gray-400 pt-9 italic">No ads. No fuss.</h3>
    </div>
    <br><br><br>
    <div x-show=!selectedFile @click="$refs.fileInput.click()" class="max-w-4xl mx-auto bg-white border-2 border-solid border-black-300 rounded-2xl shadow-sm p-30 text-center cursor-pointer hover:border-dashed transition">
        
        <div class="mx-auto w-26 h-26 bg-red-100 rounded-full flex items-center justify-center mb- animate-bounce place-items-center">
            <span class="text-red-500 text-2xl w-10 h-10 place-items-center">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-10">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9.75v6.75m0 0-3-3m3 3 3-3m-8.25 6a4.5 4.5 0 0 1-1.41-8.775 5.25 5.25 0 0 1 10.233-2.33 3 3 0 0 1 3.758 3.848A3.752 3.752 0 0 1 18 19.5H6.75Z" />
                </svg>
            </span>
        </div>

        <p class="font-bold text-gray-900 text-2xl pt-8 px-15">Insert file</p>
        <input type="file" @change="handleFileSelect($event)" x-ref="fileInput" class="hidden">
    </div>

    <div x-show="selectedFile" class="max-w-lg mx-auto mt-4">
    
    <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-4 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-full flex items-center justify-center" :class="status === 'completed' ? 'bg-green-100' : 'bg-red-100'">
                <span :class="status === 'completed' ? 'text-green-500' : 'text-red-500'">📄</span>
            </div>
            <div>
                <template x-if="status !== 'completed' && conversionType">
                    <div>
                        <p class="font-semibold text-gray-900 text-sm" x-text="selectedFile?.name"></p>
                        <p class="text-xs text-gray-400" x-text="(selectedFile?.size / 1024).toFixed(1) + ' KB'"></p>
                    </div>
                </template>
                <template x-if="status === 'completed'">
                    <div>
                        <p class="font-semibold text-gray-900 text-sm">
                            <span x-text="selectedFile?.name.split('.')[0]"></span>.<span x-text="targetFormat"></span>
                        </p>
                        <p class="text-xs text-green-600 font-medium">Your file is ready for download</p>
                    </div>
                </template>
                <p x-show="formatError" x-text="formatError" class="text-red-500 text-sm text-center mt-4"></p>
            </div>
    </div>
    <button x-show="status !== 'completed'" @click="selectedFile = null" class="text-gray-400 hover:text-gray-600">✕</button>
    <button x-show="status === 'completed'" @click="selectedFile = null; this.status = 'idle'; this.targetFormat = ''; this.conversionId = null" class="text-gray-400 hover:text-gray-600">✕</button>
</div>

      <template x-if="status === 'idle' && conversionType">
        <div>
            <div class="grid grid-cols-2 gap-4 mt-4">
                <div>
                    <label class="text-sm text-gray-500">Detected type</label>
                    <div class="mt-1 bg-gray-100 rounded-lg px-4 py-2 text-gray-800" x-text="conversionType"></div>
                </div>
                <div>
                    <label class="text-sm text-gray-500">Convert to</label>
                    <select x-model="targetFormat" class="mt-1 w-full bg-gray-100 rounded-lg px-4 py-2 text-gray-800">
                        <option value="">Select</option>
                        <template x-for="format in availableFormats" :key="format">
                            <option :value="format" x-text="'.' + format"></option>
                        </template>
                    </select>
                </div>
            </div>
            <button @click="submitConversion()" x-show="targetFormat" class="w-full mt-6 bg-red-600 text-white font-semibold py-3 rounded-full hover:bg-red-700 transition">
                Convert
            </button>
        </div>
    </template>
        <div x-show="status === 'processing'" class="mt-6">
            <p class="text-sm text-gray-500 mb-2">Converting your file...</p>
            <div class="w-full bg-gray-200 rounded-full h-2 overflow-hidden">
                <div class="bg-red-600 h-2 rounded-full animate-pulse w-full"></div>
            </div>
        </div>

        <div x-show="status === 'completed'" class="mt-6 text-center">
            <button @click="downloadFile()" class="w-full bg-red-600 text-white font-semibold py-3 rounded-full hover:bg-red-700 transition">
                Download
            </button>
        </div>

        <div x-show="status === 'failed'" class="mt-6 text-center">
            <p class="text-red-600 font-semibold" x-text="errorMessage"></p>
        </div>
    </div>

    <script>
        function converterApp(){
            return{
                
                selectedFile: null,
                status: 'idle',
                sourceFormat: null,
                targetFormat: '',
                availableFormats: [],
                conversionType: null,
                pollInterval: null,
                errorMessage: '',
                formatError: '',

               async handleFileSelect(event){

                    const file = event.target.files[0];
                    if(!file) return;
   
                    this.selectedFile = file;
                    this.sourceFormat = file.name.split('.').pop().toLowerCase();
                    this.targetFormat = ''
                    this.status = 'idle';

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
                            this.formatError = '';

                            return;
                        }
                    };

                    this.conversionType = null;
                    this.availableFormats = [];
                    this.formatError = `".${this.sourceFormat}" format is not supported for conversion. Supported Formats: ${this.getSupportedFormats(config)}`;
                },

                getSupportedFormats(config){
                    const all = new Set();

                    for(const data of Object.values(config)){
                        Object.keys(data.formats).forEach(format => all.add(format));
                    };

                    return Array.from(all).join(', ');
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
                    this.status = 'processing';
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
                                this.status = 'failed';
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