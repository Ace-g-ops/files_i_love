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

        


    }
}
