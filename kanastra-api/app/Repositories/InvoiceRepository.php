<?php
namespace App\Repositories;

use App\Contracts\InvoiceRepositoryContract;
use App\Models\Invoice;
use Yajra\DataTables\DataTables;

class InvoiceRepository implements InvoiceRepositoryContract {
    public function __construct(
        protected Invoice $model
    ){}

    public function datatable()
    {
        $model = $this->model->query();

        return DataTables::of($model)->toJson();
    }

}
