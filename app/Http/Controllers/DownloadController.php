<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Conversion;

class DownloadController extends Controller
{
    public function show(int $id){

        $conversion = Conversion::findOrFail($id);

        if($conversion->status !== 'completed'){

            return response()->json([

                'error_message' => 'File not completed',
                202,
            ]);
        }

        return response()->download($conversion->converted_path);
    }
}
