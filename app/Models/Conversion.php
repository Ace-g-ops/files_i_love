<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Conversion extends Model
{
    protected $fillable = [
        'original_filename',
        'stored_path',
        'converted_path',
        'source_format',
        'target_format',
        'status',
        'error_message',
    ];
}
