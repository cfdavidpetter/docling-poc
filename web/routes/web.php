<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FileController;

Route::get('/', [FileController::class, 'showForm'])->name('upload.form');
Route::get('/upload', [FileController::class, 'showForm'])->name('upload.form');
Route::post('/upload', [FileController::class, 'handleUpload'])->name('upload.handle');
