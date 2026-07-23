<?php
//map of allowed converted format pairs such .pdf to .docx, .docx to .pdf, .mp3 to .mp4(vice-versa) and .aac to .mp3/4.

return [

    'document' => [

        'engine' => \App\Services\DocumentConverter::class,
        'fomats' => [
            'pdf' => ['docx', 'txt', 'html'],
            'docx' => ['pdf', 'txt', 'html'],
            'txt' => ['pdf', 'docx', 'html'],
            'html' => ['pdf', 'docx', 'txt'],
        ],
    ],

    'media' => [

        'engine' => \App\Services\MediaConverter::class,
        'formats' => [
            'mp3' => ['mp4', 'aac', 'wav'],
            'mp4' => ['mp3', 'aac', 'wav'],
            'aac' => ['mp3', 'mp4'],
            'wav' => ['mp3', 'mp4'],
        ],
    ],
];