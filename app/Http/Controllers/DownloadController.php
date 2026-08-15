<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\mediaModel;
use App\Models\Conversion;
use Illuminate\Support\Facades\Storage;

class DownloadController extends Controller
{
    public function show(string $type, int $id)
    {
        $model = match($type) {
            'document' => Conversion::findOrFail($id),
            'media' => mediaModel::findOrFail($id),
            default => abort(404),
        };

        if($model->status !== 'completed' || empty($model->converted_path)){

            return response()->json([
                'error' => 'file not ready or conversion failed',
                'status' => $model->status,
                'error_log' => $model->error_message ?? 'No error recorded in this column.'
            ], 404);
        }

        //if the job is still running, inform the API consumer.

        if($model->status !== 'completed'){

            return response()->json([

                'error' => 'Conversion is still in progress. Please try again later.',
                'status' => $model->status
            
            ],202);
        }
        //check if the file exist on the disk and return a response
        $downloadName = pathinfo($model->original_filename, PATHINFO_FILENAME) . '.' . $model->target_format;

        return response()->download($model->converted_path, $downloadName);
    }
}
