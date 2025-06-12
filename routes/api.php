<?php

use App\Http\Controllers\Api\FileController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/test', function () {
    return response()->json(['message' => 'API is working!']);
});

Route::get('/projects/{project}/files', [FileController::class, 'index']);
Route::put('/files/{file}', [FileController::class, 'update']);
