<?php

namespace App\Contracts;

interface FileRepositoryContract {
    public function datatable();

    public function batch(array $data): void;
}
