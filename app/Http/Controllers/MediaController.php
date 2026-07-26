<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MediaController extends Controller
{   
    public function store(Request $request){

        $request->validate([
             
            'file' => 'required|file|max:51200', //50MB max
            'target_format' => 'required|string'
        ]);


         // Get the uploaded file and its original extension
        $uploadedFile = $request->file('file');
        $sourceFormat = strtolower($uploadedFile->getClientOriginalExtension());
        $targetFormat = $request->input('target_format');

        $category = $this->resolvecategory($sourceFormat, $targetFormat);

        if(!$category){

            return response()->json([
                'message' => "Invalid Format"
            ],422);

        }

        $storedPath = $uploadedFile->store('uploads');
         Log::info("File Stored");

        $media = mediaModel::create([
            'original_filename' => $uploadedFile->getClientOriginalName(),
            'stored_path' => storage_path('app/' . $storedPath),
            'source_format' => $sourceFormat,
            'target_format' => $targetFormat,
            'status' => 'pending'
        ]);

        //dispatch the appropriate job based on the category

        if(!$category === 'media'){

            ConvertMediaJob::dispatch($media->id);
        }

        Log::info("Job Dispatched");

        return response()->json([
            'message' => "File uploaded successfully",
            'media_id' => $media->id
        ],200);
    }

    private function resolvecategory($sourceFormat, $targetFormat){

        foreach(['media'] as $category){

            $formats = config("conversions.$category.formats");

            if(in_array($sourceFormat, $formats) && in_array($targetFormat, $formats)){

                return $category;
            }
        }

        return null;

        Log::info("Category Resolved");
        
    }

    public function status($id){

        $media = mediaModel::findOrFail($id);

        return response()->json([

            'status' => $media->status,
            'error_messsage' => $media->error_message,
        ]);
    }
}
