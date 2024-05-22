<?php

namespace App\Http\Controllers;

use App\Http\Requests\BatchRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Yajra\DataTables\Html\Builder;
use Log;

class InvoiceController extends Controller
{
    public function index(Request $request, InvoiceService $invoice, Builder $builder)
    {
        //
    }
}
