<?php

namespace App\Jobs;

use App\Models\Conversion;
use App\Services\DocumentConverter; 
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Services\PdfToDocxConverter;
use Illuminate\Support\Facades\Storage;

class ConvertDocumentJob implements ShouldQueue
{
   use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

   public $timeout = 600;
   public $tries = 2;

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
   public function handle(DocumentConverter $converter, PdfToDocxConverter $pdfToDocxConverter): void
{
    $conversion = Conversion::findOrFail($this->conversionId);

    $realInputPath = Storage::disk('local')->path($conversion->stored_path);

    if (!file_exists($realInputPath)) {
        $conversion->update([
            'status' => 'failed',
            'error_message' => "Input file does not exist on disk: " . $realInputPath
        ]);
        return;
    }

    $conversion->update(['status' => 'processing']);

    try {
        $outputDir = dirname($realInputPath);

        if ($conversion->source_format === 'pdf' && $conversion->target_format === 'docx') {
            $filename = pathinfo($realInputPath, PATHINFO_FILENAME);
            $outputPath = $outputDir . '/' . $filename . '.docx';

            $convertedPath = $pdfToDocxConverter->convert($realInputPath, $outputPath);
        } else {
            $convertedPath = $converter->convert($realInputPath, $outputDir, $conversion->target_format, $conversion->source_format);
        }

        $conversion->update([
            'status' => 'completed',
            'converted_path' => $convertedPath,
        ]);
    } catch (\Throwable $e) {
        $conversion->update([
            'status' => 'failed',
            'error_message' => $e->getMessage(),
        ]);
    }
}
}
