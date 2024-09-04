<?php

use App\Http\Controllers\API\Paket\PaketController;
use App\Http\Controllers\API\Penumpang\PenumpangController;
use App\Http\Controllers\API\Perjalanan\PerjalananController;
use App\Http\Controllers\API\TiketController\TiketController;
use App\Models\Paket;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/tiket/{paymentCode}', [TiketController::class, 'template']);
Route::get('/surat-jalan/{jadwalID}', [PerjalananController::class, 'suratJalan']);
Route::get('/tiket/{paymentCode}/download', [TiketController::class, 'download']);
Route::get('/invoice/{paymentCode}', [TiketController::class, 'invoice']);
Route::get('/invoice/{paymentCode}/download', [TiketController::class, 'invoiceDownload']);
Route::get('/e-tiket/{paymentCode}', [TiketController::class, 'eTiket']);
Route::get('/e-tiket/{paymentCode}/download', [TiketController::class, 'eTiketDownload']);
Route::get('/paket/label/{paymentCode}', [PaketController::class, 'downloadLabel']);

Route::group(['prefix' => 'rental'], function () {
    Route::get('/e-tiket/{paymentCode}', [TiketController::class, 'rentalTiket']);
    Route::get('/invoice/{paymentCode}', [TiketController::class, 'rentalInvoice']);
});
