<?php

namespace App\Http\Controllers;

use App\Models\Conversion;
use App\Jobs\ConvertDocumentJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Logs;
use Illuminate\Support\Facades\Storage; 
class ConversionController extends Controller
{   
    
    public function store(Request $request)
    {
         $request->validate([
            'file' => 'required|file|mime:pdf,docx,txt,html|max:51200', //50MB max
            'target_format' => 'required|string'
        ]);


        // Get the uploaded file and its original extension
        $uploadedFile = $request->file('file');
        $sourceFormat = strtolower($uploadedFile->getClientOriginalExtension());
        $targetFormat = $request->input('target_format');

        // Determine the category of conversion based on source and target formats
        $category = $this->resolveCategory($sourceFormat, $targetFormat);
        // dd($category);

        if(!$category){

            return response()->json([

                'error' => 'Unsupported Conversion',
            ],422);
        }

        $storage = $uploadedFile->store('uploads');

        $conversion = Conversion::create([
            'original_filename' => $uploadedFile->getClientOriginalName(),
            'stored_path' => $storage,
            'source_format' => $sourceFormat,
            'target_format' => $targetFormat,
            'status' => 'processing',
            'converted_path' => null,
        ]);

        // Dispatch the appropriate job based on the category
        if($category === 'document'){

            ConvertDocumentJob::dispatch($conversion->id);
        }

        return response()->json([

            'id' => $conversion->id,
            'message' => "Successfully Converted",
        ], 200);

    }
    //determine the category of conversion based on source and target formats
    private function resolveCategory(string $source, string $target): ?string
    {
        foreach(['document'] as $category){

            $formats = config("conversions.$category.formats");

            if(isset($formats[$source]) && in_array($target, $formats[$source])){

                return $category;
            }

        }

        return null;
    }

    public function status(int $id){

        $conversion = Conversion::findOrFail($id);

        return response()->json([

            'status' => $conversion->status,
            'error_message' => $conversion->error_message,
        ]);
    }

}
