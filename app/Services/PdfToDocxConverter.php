<?php

namespace App\Services;

use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class PdfToDocxConverter
{
    public function convert(string $inputPath, string $outputPath): string
    {
        $process = new Process([
            base_path('venv/bin/python3'),
            base_path('python/pdf_to_docx.py'),
            $inputPath,
            $outputPath,
        ]);

        $process->setTimeout(200); // Set a timeout of 200 seconds
        $process->run();

        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

       return $outputPath;
    }
}