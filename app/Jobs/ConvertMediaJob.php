<?php

namespace App\Jobs;

use App\Models\Conversion;
use App\Services\MediaConverter;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Bus\Dispatchable;

class ConvertMediaJob implements ShouldQueue
{
     use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(public int $conversionId)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(MediaConverter $converter): void
    {
        $conversion = Conversion::findOrFail($this->conversionId);

        $conversion->update(['status' => 'processing']);

        try{
            //strips the folder of the file.
            $outputDir = dirname($conversion->stored_path);

            $filename = pathinfo($conversion->stored_path, PATHINFO_FILENAME);
            $outputPath = $outputDir . '/' . $filename . '.' .  $conversion->target_format;

            $convertedPath = $converter->convert(
                $conversion->stored_path,
                $outputPath
            );

            $conversion->update([

                'status' => 'completed',
                'convertedPath' => $convertedPath,
            ]);
        }catch(\Throwable $e){

            $conversion-> update([

                'status' => "failed",
                'error_message' => $e->getmessage(),
            ]);
        }

    }
}
