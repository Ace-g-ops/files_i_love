<!-- handles both media and doc files conversion -->


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>FilesILove</title>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body>
    <div x-data="converterApp()">
        <h1>FilesILove</h1>
        <input type="file" @change="handleFileSelect($event)">
        <select x-show="availableFormats.length > 0" x-model="targetFormat">
            <option value=""> Select target format</option>
            <template x-for="format in availableFormats" :key="format">
                <option :value="format" x-text="format"></option> 
            </template>
        </select>    
            <button @click="submitConversion()" x-show="targetFormat">Convert</button>
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
                errorMesage: '',

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

                        if(data.status === 'complete'){
                            clearInterval(this.pollInterval);
                            this.status = 'Completed';
                        }else{

                            if(data.status == 'failed'){
                                clearInterval(pollInterval);
                                this.status = 'Failed';
                                this.errorMessage = data.error_message ?? 'Conversion Failed';
                            }
                        }
                    }, 2000);
                },
               
            }
        }
    </script>
</body>

</html>