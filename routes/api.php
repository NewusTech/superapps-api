<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\Jadwal\JadwalController;
use App\Http\Controllers\API\Kursi\KursiController;
use App\Http\Controllers\API\Laporan\LaporanController;
use App\Http\Controllers\API\MasterMobil\MasterMobilController;
use App\Http\Controllers\API\MasterCabang\MasterCabangController;
use App\Http\Controllers\API\MasterRute\MasterRuteController;
use App\Http\Controllers\API\MasterTitikJemput\MasterTitikJemputController;
use App\Http\Controllers\API\MasterSupir\MasterSupirController;
use App\Http\Controllers\API\Paket\PaketController;
use App\Http\Controllers\API\Pariwisata\PariwisataController;
use App\Http\Controllers\API\Pembayaran\PembayaranController;
use App\Http\Controllers\API\Penumpang\PenumpangController;
use App\Http\Controllers\API\Pesanan\PesananController;
use App\Http\Controllers\API\Role\RoleController;
use App\Http\Controllers\API\Users\ManageUsersController;
use App\Http\Controllers\API\Users\UserController;
use App\Http\Controllers\ArtikelController;
use App\Http\Controllers\API\Banner\BannerController;
use App\Http\Controllers\API\MobilRental\MobilRentalController;
use App\Http\Controllers\API\Penginapan\PenginapanController;
use App\Http\Controllers\API\Perjalanan\PerjalananController;
use App\Http\Controllers\API\Printer\PrinterController;
use App\Http\Controllers\API\Rental\RentalController;
use App\Http\Controllers\API\SyaratKetentuan\SyaratKetentuanController;
use App\Http\Controllers\API\TiketController\TiketController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and a_ll of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function () {
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/register', [AuthController::class, 'register'])->name('register');
    Route::resource('/user-profile', UserController::class);
    Route::put('/update-profile', [UserController::class, 'updateProfile'])->name('update-profile');
});

Route::group(['middleware' => 'api','prefix' => 'rute'], function () {
    Route::resource('master_rute', MasterRuteController::class);
    Route::post('master_rute/{id}', [MasterRuteController::class, 'update']);
    Route::get('dropdown', [MasterRuteController::class, 'dropdown']);
});

Route::group(['middleware' => 'api', 'prefix' => 'master_penginapan'], function () {
    Route::resource('penginapan', PenginapanController::class);
});

Route::group(['middleware' => 'api', 'prefix' => 'mobil'], function () {
    Route::resource('master_mobil', MasterMobilController::class);
    Route::patch('{id}/update-status', [MasterMobilController::class, 'updateStatus']);
});


Route::group(['middleware' => 'api', 'prefix' => 'cabang'], function () {
    Route::resource('master_cabang', MasterCabangController::class);
});

Route::group(['middleware' => 'api', 'prefix' => 'titik_jemput'], function () {
    Route::resource('master_titik_jemput', MasterTitikJemputController::class);
});

Route::group(['middleware' => 'api', 'prefix' => 'supir'], function () {
    Route::resource('master_supir', MasterSupirController::class);
});

Route::group(['middleware' => 'api', 'prefix' => 'kursi'], function () {
    Route::resource('kursi', KursiController::class);
    Route::get('kursi_by_mobil/{id}', [KursiController::class, 'getKursiByMobil']);
    Route::put('{id}/update-status', [KursiController::class, 'updateStatus']);
});

Route::group(['middleware' => 'api', 'prefix' => 'paket'], function () {
    Route::resource('paket', PaketController::class);
    Route::get('status-pembayaran/{kode_paket}', [PaketController::class, 'getStatusPembayaranByKodePaket']);
    Route::post('proses-pembayaran', [PaketController::class, 'prosesPembayaranPaket']);
    Route::get('label/download/{resi}', [PaketController::class, 'label']);
    Route::patch('status-pembayaran/update/{resi}', [PaketController::class, 'updateStatusPembayaran']);
});

Route::group(['middleware' => 'api', 'prefix' => 'jadwal'], function () {
    Route::resource('jadwal', JadwalController::class);
    Route::get('tanggal/{tanggal}', [JadwalController::class, 'getJadwalPerTanggal']);
    Route::get('dropdown-jadwal', [JadwalController::class, 'dropdownJadwal']);
    Route::get('jadwal_by_rute', [JadwalController::class, 'getJadwalByRute']);
});

Route::group(['middleware' => 'api', 'prefix' => 'syarat-ketentuan'], function () {
    Route::resource('syarat-ketentuan', SyaratKetentuanController::class);
});

Route::group(['middleware' => 'api', 'prefix' => 'pesanan'], function () {
    Route::resource('pesanan', PesananController::class);
    Route::get('tiket/{orderCode}', [TiketController::class, 'download']);
    Route::get('e-tiket/{orderCode}', [TiketController::class, 'eTiketDownload']);
    Route::get('invoice/{orderCode}', [TiketController::class, 'invoiceDownload']);
    Route::get('user', [PesananController::class, 'pesananByUserId']);
    Route::get('riwayat', [PesananController::class, 'getAllHistoryPesanan']);
    Route::get('riwayat/{orderCode}', [PesananController::class, 'getDetailPesanan']);
    Route::post('konfirmasi_pesanan', [PesananController::class, 'konfirmasiPesanan']);
});

Route::group(['middleware' => 'api'], function () {
    Route::resource('pariwisata', PariwisataController::class);
});

Route::group(['middleware' => 'api', 'prefix' => 'rental'], function () {
    Route::resource('rental', RentalController::class);
    Route::patch('status-pembayaran/update/{paymentCode}', [RentalController::class, 'updateStatusPembayaran']);
    Route::get('riwayat', [RentalController::class, 'riwayat']);
    Route::get('riwayat/{paymentCode}', [RentalController::class, 'detailRental']);
    Route::resource('mobil', MobilRentalController::class);
    Route::post('process-payment', [RentalController::class, 'processPayment']);
    Route::get('booked-dates', [RentalController::class, 'getBookedDates']);
});

Route::group(['middleware' => 'api', 'prefix' => 'perjalanan'], function () {
    Route::get('list-penumpang', [PerjalananController::class, 'index']);
});

Route::group(['middleware' => 'api', 'prefix' => 'penumpang'], function () {
    Route::resource('penumpang', PenumpangController::class);
});

Route::group(['middleware' => 'api', 'prefix' => 'users'], function () {
    Route::resource('manage_user', ManageUsersController::class);
});
Route::group(['middleware' => 'api', 'prefix' => 'roles'], function () {
    Route::resource('role', RoleController::class);
    Route::get('permission', [RoleController::class, 'getAllPermission']);
});
Route::group(['middleware' => 'api', 'prefix' => 'laporan'], function () {
    Route::get('laporan_per_mobil', [LaporanController::class, 'laporanPesananPerMobil']);
    Route::get('laporan', [LaporanController::class, 'laporanPesananPerRute']);
    Route::get('/', [LaporanController::class, 'laporanPesananPerRute']);
    Route::get('/{id}', [LaporanController::class, 'detail']);
});

Route::group(['middleware' => 'api', 'prefix' => 'pembayaran'], function () {
    Route::get('pembayaran/{pesanan_id}', [PembayaranController::class, 'showPembayaran']);
    Route::post('proses_pembayaran', [PembayaranController::class, 'prosesPembayaran']);
    Route::post('tes_proses_pembayaran', [PembayaranController::class, 'testProsesPembayaran']);
    Route::get('status/{paymentCode}', [PembayaranController::class, 'getStatusPembayaran']);
    Route::patch('update-status/{orderCode}', [PembayaranController::class, 'updateStatusPembayaran']);
    Route::get('metode-pembayaran', [PembayaranController::class, 'getMetodePembayaran']);
    Route::post('metode-pembayaran', [PembayaranController::class, 'storeMetodePembayaran']);
    Route::delete('metode-pembayaran/{id}', [PembayaranController::class, 'deleteMetodePembayaran']);
    Route::post('midtrans-notification', [PembayaranController::class, 'handleMidtransNotification']);
});

Route::group(['middleware' => 'api', 'prefix' => 'artikel'], function () {
    Route::resource('artikel', ArtikelController::class);
    Route::get('/rekomendasi', [ArtikelController::class, 'rekomendasi']);
});

Route::group(['middleware' => 'api', 'prefix' => 'banner'], function () {
    Route::resource('banner', BannerController::class);
});

Route::group(['middleware' => 'api', 'prefix' => 'printer'], function () {
    // Route::post('print', [PrinterController::class, 'print']);
    Route::post('print/{paymentCode}', [PrinterController::class, 'print']);
});
