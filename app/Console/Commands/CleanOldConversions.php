<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Conversion;
use App\Models\mediaModel;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class CleanOldConversions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:clean-old-conversions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete conversion records and files older than 24hrs';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Calculate the cutoff time (24 hours ago)
        $cutoff = now()->subHours(24);
        
        foreach([Conversion::class, MediaModel::class] as $model){
                $oldRecords = $model::where('created_at', '<', $cutoff)->get();
            // Loop through the old records and delete them along with their associated files
            foreach($oldRecords as $record){
                 $realStoredPath = Storage::disk('local')->path($record->stored_path);

                 if(File::exists($realStoredPath) ) {
                    File::delete($realStoredPath);
                 }

                 if($record->converted_path && File::exists($record->converted_path)){
                    File::delete($record->converted_path);
                 }

                 $record->delete();

            }
        };
    }
}
