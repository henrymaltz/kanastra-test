<?php

namespace App\Jobs;

use App\Models\Invoice;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Bus;

class ProcessFile implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The file rows.
     *
     * @var array
     */
    public $invoice;

    /**
     * The file header.
     *
     * @var array
     */
    public $header;


    /**
     * Create a new job instance.
     */
    public function __construct(array $invoice, array $header)
    {
        $this->invoice = $invoice;
        $this->header = $header;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $invoice = new Collection();

        $batch = Bus::batch([])->name('files')->onQueue('files')->dispatch();

        foreach ($this->invoice as $invoice) {
            $inputInvoice = array_combine($this->header, $invoice);
            $model = Invoice::create($inputInvoice);

            $batch->add(new ProcessInvoice($model));
        }
    }
}