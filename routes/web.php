<?php

use App\Http\Controllers\API\TiketController\TiketController;
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
Route::get('/tiket/{paymentCode}/download', [TiketController::class, 'download']);
Route::get('/invoice/{paymentCode}', [TiketController::class, 'invoice']);
Route::get('/invoice/{paymentCode}/download', [TiketController::class, 'invoiceDownload']);
