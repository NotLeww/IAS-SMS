<?php

use App\Http\Controllers\DocumentRequestController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/documents/request', [DocumentRequestController::class, 'studentForm'])->name('documents.request.form');
Route::post('/documents/request', [DocumentRequestController::class, 'store'])->name('documents.request.store');

Route::get('/admin/documents', [DocumentRequestController::class, 'adminIndex'])->name('admin.documents.index');
Route::get('/admin/documents/archived', [DocumentRequestController::class, 'archiveIndex'])->name('admin.documents.archived');
Route::get('/admin/documents/{documentRequest}', [DocumentRequestController::class, 'show'])->name('admin.documents.show');
Route::post('/admin/documents/{documentRequest}/process', [DocumentRequestController::class, 'process'])->name('admin.documents.process');
Route::post('/admin/documents/{documentRequest}/approve', [DocumentRequestController::class, 'approve'])->name('admin.documents.approve');
Route::post('/admin/documents/{documentRequest}/reject', [DocumentRequestController::class, 'reject'])->name('admin.documents.reject');
Route::post('/admin/documents/{documentRequest}/release', [DocumentRequestController::class, 'release'])->name('admin.documents.release');
Route::post('/admin/documents/{documentRequest}/archive', [DocumentRequestController::class, 'archive'])->name('admin.documents.archive');
Route::post('/admin/documents/{documentRequest}/restore', [DocumentRequestController::class, 'restore'])->name('admin.documents.restore');
Route::get('/admin/documents/{documentRequest}/preview', [DocumentRequestController::class, 'preview'])->name('admin.documents.preview');
Route::get('/admin/documents/{documentRequest}/download', [DocumentRequestController::class, 'download'])->name('admin.documents.download');
