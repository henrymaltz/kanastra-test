<?php

namespace App\Services;

use App\Repositories\FileRepository;
use Illuminate\Http\UploadedFile;
use JamesGordo\CSV\Parser;
use App\Models\Invoice;
use App\Models\File;
use Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Bus;
use App\Jobs\ProcessFile;

class FileService {
    public function __construct(
        protected FileRepository $fileRepository,
        protected SqsService        $sqsService
    ){}

    public function datatable()
    {
        return $this->fileRepository->datatable();
    }

   public function executeFile(UploadedFile $file): string {
       set_time_limit(0);

       $path = $file->getRealPath();

       if (file_exists($path)) {
            $filename = $file->getClientOriginalName();
            $lines = 0;

            $data = file($file);
            $lines = count($data);
            $chunks = array_chunk($data, 5000);

            $header = [];
            $batch  = Bus::batch([])->name('files')->onQueue('files')->dispatch();
    
            foreach ($chunks as $key => $chunk) {
                $data = array_map('str_getcsv', $chunk);
    
                if ($key === 0) {
                    $header = $data[0];
                    unset($data[0]);
                }
                $batch->add(new ProcessFile($data, $header));
            }            

            $file = File::create(
            [
                'name' => $filename,
                'lines' => $lines
            ]);

            return json_encode([
                'status' => true,
            ], 200);
      
       } else {
            return json_encode([
                'status' => false,
            ], 404);
       }
   }

    public function batch(array $data): void {
         $this->fileRepository->batch($data);
    }
}
