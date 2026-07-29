<?php

namespace App\Jobs;

use App\Models\mediaModel;
use App\Services\MediaConverter;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Storage;

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
    $conversion = mediaModel::findOrFail($this->conversionId);

    $realInputPath = Storage::disk('local')->path($conversion->stored_path);

    if (!file_exists($realInputPath)) {
        $conversion->update([
            'status' => 'failed',
            'error_message' => "PHP says this path does not exist on disk: " . $realInputPath
        ]);
        return;
    }

    $conversion->update(['status' => 'processing']);

    try {
        // Generate an absolute output path in the same secure directory
        $filename = pathinfo($realInputPath, PATHINFO_FILENAME);
        $outputRelativePath = 'uploads/' . $filename . '.' . $conversion->target_format;
        $realOutputPath = Storage::disk('local')->path($outputRelativePath);

        // Run the converter with fully resolved absolute system paths
        $convertedPath = $converter->convert($realInputPath, $realOutputPath);

        $conversion->update([
            'status' => 'completed',
            'converted_path' => $outputRelativePath, // Keep saving relative reference to DB
        ]);

    } catch(\Throwable $e) {
        $conversion->update([
            'status' => "failed",
            'error_message' => $e->getMessage(),
        ]);
    }
}
}
