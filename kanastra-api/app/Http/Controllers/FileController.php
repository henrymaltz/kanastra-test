<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Http\Requests\BatchRequest;
use App\Http\Requests\FileRequest;
use App\Services\FileService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Yajra\DataTables\Html\Builder;
use Log;


class FileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(FileRequest $request, FileService $file)
    {
        $execute = json_decode($file->executeFile($request->file('file')), true);
        if ($execute['status']) {
            return response()->json(['success' => 'success'], 200);
        } else {
            return response()->json(['error' => 'error'], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(BatchRequest $request, FileService $file)
    {
        return response()->json($file->datatable()->getData());
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(File $file)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, File $file)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(File $file)
    {
        //
    }
}
