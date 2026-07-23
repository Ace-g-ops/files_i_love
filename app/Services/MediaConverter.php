<?php

//this handles the media conversion such as audio files from one format to another format.
namespace App\Services;

use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class mediaConverter{

    public function convert(string $inputPath, string $outputPath): string
    {

        $process = new Process([
            'ffmpeg',
            '-i', '$inputPath',
            '-y', // overwrite if exists
            $outputPath,
        ]);

        $process->setTimeout(300); //audio/video conversion do take time hence why 3,mins
        $process->run();

        if(!$process->isSuccessful()){

            throw new ProcessFailedException($process);
        }

        return $outputPath;
    }
}