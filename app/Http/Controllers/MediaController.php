<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Logs;
use App\Jobs\ConvertMediaJob;
use App\Models\mediaModel;
use Illuminate\Support\Facades\Storage;

class MediaController extends Controller
{   
    public function store(Request $request){

        $request->validate([
             
            'file' => 'required|file|mime:mp3,mp4,aac,wav|max:51200', //50MB max
            'target_format' => 'required|string'
        ]);


         // Get the uploaded file and its original extension
        $uploadedFile = $request->file('file');
        $sourceFormat = strtolower($uploadedFile->getClientOriginalExtension());
        $targetFormat = $request->input('target_format');

        $category = $this->resolveCategory( $sourceFormat, $targetFormat);

        if(!$category){

            return response()->json([
                'error' => "Invalid Format"
            ],422);

        }

        $storage = $uploadedFile->store('uploads'); //this returns the flename.

        $media = mediaModel::create([
            'original_filename' => $uploadedFile->getClientOriginalName(),
            'stored_path' => $storage,
            // storage_path('app/' . $storedPath),
            'source_format' => $sourceFormat,
            'target_format' => $targetFormat,
            'status' => 'processing', // Set initial status to 'pending' or 'processing' based on your logic
            'converted_path' => null, // Initially, there is no converted path
        ]);

        //dispatch the appropriate job based on the category

        if($category === 'media'){

            ConvertMediaJob::dispatch($media->id);

        }

        return response()->json([
            'message' => "File uploaded successfully",
            'media_id' => $media->id
        ],200);
    }
    //determine the category of conversion based on source and target formats
    private function resolveCategory(string $sourceFormat, string $targetFormat): ?string
    {
        foreach(['media'] as $category){

            $formats = config("conversions.$category.formats");

            if(isset($formats[ $sourceFormat]) && in_array($targetFormat, $formats[ $sourceFormat])){

                return $category;
            }

        }

        return null;
    }

    public function status($id){

        $media = mediaModel::findOrFail($id);

        return response()->json([

            'status' => $media->status,
           'error_message' => $media->error_message,
        ]);
    }
}
