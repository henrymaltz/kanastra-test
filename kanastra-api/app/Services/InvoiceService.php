<?php

namespace App\Services;

use App\Repositories\InvoiceRepository;
use Illuminate\Http\UploadedFile;
use JamesGordo\CSV\Parser;
use App\Models\Invoice;
use App\Models\File;
use Log;
use Illuminate\Support\Str;

class InvoiceService {
    public function __construct(
        protected InvoiceRepository $invoiceRepository
    ){}

}
