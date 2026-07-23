<?php

namespace App\Jobs;

use App\Models\Conversion;
use App\Services\DocumentConverter; 
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Bus\Dispatchable;

class ConvertDocumentJob implements ShouldQueue
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
    public function handle(DocumentConverter $converter): void
    {
        //this looks up teh database to se if the id exists and if the id dosent exist, it throws an error.
        $conversion = Conversion::findOrFail($this->conversionId);

        $conversion->update(['status' => 'processing']);

        try {

            //strips the folder location and reveals the exact file path.
            $outputDir = dirname($conversion->stored_path);

            //call the services for what it needs exactly.
            $convertedPath = $converter->convert(
                $conversion-> stored_path,
                $outputDir,
                $conversion-> target_format
            );

            $conversion->update([

                'status' => 'completed',
                'convertedPath' => $convertedPath,
            ]);

        }catch(\Throwable $e) {

            $conversion->update([
                'status' => 'failed',
                'error_message' => $e->getMessage(),
            ]);
        }
    }
}
