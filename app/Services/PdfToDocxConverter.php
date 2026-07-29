<?php

namespace App\Services;

use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class PdfToDocxConverter
{
    public function convert(string $inputPath, string $outputDir): string
    {
        $process = new Process([
            'python3',
            base_path('python/pdf_to_docx.py'),
            $inputPath,
            $outputDir
        ]);

        $process->setTimeout(200); // Set a timeout of 200 seconds
        $process->run();

        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

       return $ouputPath;
    }
}