<?php

namespace App\Services;

use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class documentConverter{

    public function convert(string $inputPath, string $outputDir, string $targetFormat, string $sourceFormattail ): string
    {
        $args = ['soffice'];

        if ($sourceFormat === 'pdf') {
            $args[] = '--infilter=writer_pdf_import';
        }

        $args = array_merge($args, [
            '--headless',
            '--convert-to', $targetFormat,
            '--outdir', $outputDir,
            $inputPath,
        ]);

        $process = new Process($args);

        $process->setTimeout(600); // Set a timeout of 600 seconds
        $process->run();

       if(! $process->isSuccessful()){
            throw new ProcessFailedException($process);
        };

        $filename =  pathinfo($inputPath, PATHINFO_FILENAME);
        return $outputDir . '/' . $filename . '.' . $targetFormat;
    }
}
