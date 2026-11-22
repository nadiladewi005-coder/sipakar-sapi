<?php

use App\Http\Controllers\DiagnosaController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DiagnosaController::class, 'beranda'])->name('beranda');
Route::post('/diagnosa', [DiagnosaController::class, 'proses'])->name('diagnosa.proses');
Route::get('/diagnosa/{diagnosa}', [DiagnosaController::class, 'hasil'])->name('diagnosa.hasil');
Route::get('/riwayat', [DiagnosaController::class, 'riwayat'])->name('riwayat');
