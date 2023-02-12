<?php

namespace App\Jobs;

use App\Imports\ProductImport;
use App\Models\ProductCategory;
use App\Models\Collection;
use App\Models\Join_Category_Product;
use App\Models\ProductImportHistory;
use App\Models\ProductStock;
use App\Models\Theme;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use InApps\IAModules\Helpers\LogHelper;
use Maatwebsite\Excel\Facades\Excel;
use DateInterval;
use DatePeriod;
use Carbon\Carbon;
use Illuminate\Support\Str;

class ProcessImportData implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $timeout = 0;
    public $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $file_location = $this->data;
            $array = Excel::toArray(new ProductImport, base_path($file_location));
            LogHelper::debug('Start Import');
            $data = $array[0];
            $chunks = array_chunk($data, 200);
            foreach ($chunks as $index => $chunk) {
                ImportProduct::dispatch($chunk, count($chunks) === ($index + 1));
            }
            LogHelper::debug('Import Done ');
            ProductImportHistory::where('file_location', $file_location)->update(
                [
                    'process' => 1,
                    'message' => 'Done'
                ]
            );
        } catch (\Exception $e) {
            ProductImportHistory::where('file_location', $file_location)->update(
                [
                    'process' => 2,
                    'message' => json_encode($e->getMessage())
                ]
            );
            LogHelper::error('Import file error', ['message' => $e->getMessage()]);
        }
    }
}
