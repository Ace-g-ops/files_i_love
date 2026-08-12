<!-- handles both media and doc files conversion -->


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>File Converter</title>
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
                selectedfile: null,
                sourceFormat: null,
                targetFormat: '',
                availableFormats: [],
                conversionType: null,

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
                    formData.append('file', this.selectedfile);
                    formData.append('target_format', this.targetFormat);

                    const endpoint = this.conversionType === 'document'
                        ? '/api/convert'
                        :'/api/convert-media';

                    const response = await fetch(endpoint,{

                        method: 'POST',
                        body: formData,
                    });

                    const data = await response.json();

                    
                    if(!response.ok){
                        this.status = 'failed';
                        console.error('Upload Data:', data);
                        return;
                    }

                    this.conversionId = data.conversion_id ?? this.media_id;
                    this.status = 'Processing';

                    this.conversionId = null;
                }
               
            }
        }
    </script>
</body>

</html>