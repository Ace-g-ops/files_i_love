<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class mediaModel extends Model
{
    protected $fillable = [

        'original_filename',
        'stored_path',
        'converted_path',
        'error_message'.
        'source_format',
        'target_format',
        
    ];
}
