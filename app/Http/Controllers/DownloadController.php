<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\mediaModel;
use App\Models\Conversion;
use Illuminate\Support\Facades\Log;

class DownloadController extends Controller
{
  public function show(string $type ,int $id){

        dd($type, $id);

    }
}


        // $model = match($type){

        //     'document' => Conversion::findOrFail($id),
        //     'media' => mediaModel::findOrFail($id),
        //     default => abort(404),
        // };

        // if($model->status !== 'completed'){

        //    abort(404, 'File not available for download');      
        // }

        // return response()->download($model->converted_path, $model->original_filename);