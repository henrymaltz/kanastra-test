<?php
namespace App\Repositories;

use App\Contracts\FileRepositoryContract;
use App\Models\File;
use Yajra\DataTables\DataTables;

class FileRepository implements FileRepositoryContract {
    public function __construct(
        protected File $model
    ){}

    public function datatable()
    {
        $model = $this->model->query();

        return DataTables::of($model)->toJson();
    }

   public function batch(array $data): void {
       foreach ($data as $item) {
           $this->model->insert($item);
       }
   }
}
