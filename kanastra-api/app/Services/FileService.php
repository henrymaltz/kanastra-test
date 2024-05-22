<?php

namespace App\Services;

use App\Repositories\FileRepository;
use Illuminate\Http\UploadedFile;
use JamesGordo\CSV\Parser;
use App\Models\Invoice;
use App\Models\File;
use Log;
use Illuminate\Support\Str;

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
       $path = $file->getRealPath();

       if (file_exists($path)) {

            $filename = $file->getClientOriginalName();
            $lines = 0;
            $skipHeader = true;
            $file_handle = fopen($file, 'r');
            while ($csvRow = fgetcsv($file_handle, null)) {
                $lines = $lines + 1;            
                if ($skipHeader) {
                    $skipHeader = false;
                    continue;
                }            
                $name = $csvRow[0];
                $governmentId = $csvRow[1];
                $email = $csvRow[2];
                $debtAmount = $csvRow[3];
                $debtDueDate = $csvRow[4];
                $debtId = $csvRow[5];
                try{
                    $invoice = Invoice::create(
                    [
                        'name' => $name,
                        'governmentId' => $governmentId,
                        'email' => $email,
                        'debtAmount' => $debtAmount,
                        'debtDueDate' => $debtDueDate,
                        'debtId' => $debtId
                    ]);
                    
                } catch (\Exception $e) {
                    Log::error("Error creating debt: [debtIdid::{$debtId}] with message ". $e->getMessage());
                }
            }
            fclose($file_handle);

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
