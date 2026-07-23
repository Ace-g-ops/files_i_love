<?php

namespace App\Http\Controllers;

use App\Models\Conversion;
use App\Jobs\ConvertDocumentJob;
use App\Jobs\ConvertMediaJob;
use Illuminate\Http\Request;

class ConversionController extends Controller
{
    public function store(Request $request)
    {
        //this request for the file and also validate its input.
        $request->validate([
            'file' => 'required|file|max:51200',
            'target_format' => 'required|string'
        ]);


    }

}
