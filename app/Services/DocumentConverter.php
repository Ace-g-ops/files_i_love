<?php

namespace App\Services;

use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class documentConverter{

    public function convert(string $inputPath, string $outputDir, string $targetFormat): string
    {
        $process = new Process([
            'soffice',
            '--infilter', 'writer_pdf_import',
            '--headless',
            '--convert-to', $targetFormat,
            '--outdir', $outputDir,
            $inputPath
        ]);

        $process->setTimeout(120); // Set a timeout of 120 seconds
        $process->run();

       if(! $process->isSuccessful()){
            throw new ProcessFailedException($process);
        };

        $filename =  pathinfo($inputPath, PATHINFO_FILENAME);
        return $outputDir . '/' . $filename . '.' . $targetFormat;
    }
}
