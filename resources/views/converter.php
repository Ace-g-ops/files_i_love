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
        <p x-text="status"></p>
        <input type="file" @change="handleFileSelect($event)">
    </div>

    <script>
        function converterApp(){
            return{
                status: 'idle',
                selectedfile: null,
                sourceFormat: null,

                handleFileSelect(event){

                    const file = event.target.files[0];
                    if(!file) return;

                    this.selectedFile = file;
                    this.sourceFormat = file.name.split(' . ').pop.toLowerCase()

                    console.log('File Selected:', file.name, 'Format:', this.sourceFormat)
                }
            }
        }
    </script>
</body>

</html>