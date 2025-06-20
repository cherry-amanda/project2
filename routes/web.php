<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LandingController;
// use App\Http\Controllers\c_booking;
use App\Http\Controllers\C_Admin;
use App\Http\Controllers\C_Klien;
use App\Http\Controllers\c_date;
use App\Http\Controllers\c_event;
use App\Http\Controllers\c_package;
use App\Http\Controllers\c_vendor;
use App\Http\Controllers\c_vendorservice;
use App\Http\Controllers\c_vendor_profile;
use App\Http\Controllers\c_pengguna;
use App\Http\Controllers\c_invoice;
use App\Http\Controllers\c_profile_klien;
use App\Http\Controllers\c_keuangan;
// use App\Http\Controllers\c_pembayaran;
use App\Http\Controllers\c_payment;
use App\Http\Controllers\c_vendorBooking;
use App\Http\Controllers\c_vendorServiceAdmin;
use App\Http\Controllers\c_cart;
use App\Http\Controllers\c_pesanan;



/*
|--------------------------------------------------------------------------
| Auth Routes
|--------------------------------------------------------------------------
*/
Route::get('login', [AuthController::class, 'showLogin'])->name('login');
Route::post('login', [AuthController::class, 'login']);
Route::get('register', [AuthController::class, 'showRegister'])->name('register');
Route::post('register', [AuthController::class, 'register']);
Route::post('logout', [AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| Landing Page (Klien)
|--------------------------------------------------------------------------
*/
Route::get('/home', [LandingController::class, 'index'])->name('klien');

/*
|--------------------------------------------------------------------------
| Dashboard Admin & Vendor & Klien
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/admin/dashboard', [C_Admin::class, 'dashboard'])->name('admin.dashboard');
     

    Route::get('/dashboard-vendor', function () {
        return view('vendor.v_dashboard-vendor');
    })->name('vendor.dashboard');

    Route::get('/dashboard-klien', function () {
        return view('klien.v_dashboard-klien');
    })->name('klien.dashboard');
});


Route::get('/', function () {
    return view('landingpage');
})->name('landing');

Route::get('/galeri', function () {
    return view('galeri');
})->name('galeri');

Route::get('/testimoni', function () {
    return view('testimoni');
})->name('testimoni');



/*
|--------------------------------------------------------------------------
| Fitur yang ada di klien
|--------------------------------------------------------------------------
*/
Route::prefix('klien')->middleware('auth')->group(function () {

  

    // ==========Dashboard klien ==========
    Route::get('/dashboard', [C_Klien::class, 'dashboard'])->name('klien.dashboard');
    Route::get('/dashboard/data', [C_Klien::class, 'dashboardData'])->name('klien.dashboard.data');



     // ==========Profile klien ==========
    Route::get('profile', [c_profile_klien::class, 'index'])->name('klien.profile');
    Route::get('profile/edit', [c_profile_klien::class, 'edit'])->name('klien.profile.edit');
    Route::put('profile/update', [c_profile_klien::class, 'update'])->name('klien.profile.update');





    // ==========Booking klien ==========
    Route::get('/booking', [c_cart::class, 'index'])->name('klien.booking.index');
    Route::get('/booking/cart', [c_cart::class, 'cart'])->name('klien.cart');
    Route::post('/booking/add-to-cart/{id}', [c_cart::class, 'addToCart'])->name('klien.cart.add');
    Route::post('/booking/remove-from-cart/{id}', [c_cart::class, 'removeFromCart'])->name('klien.cart.remove');
    Route::get('/booking/pesan/{id}', [c_cart::class, 'checkoutNow'])->name('klien.checkout.now');
    Route::get('/booking/checkout', [c_cart::class, 'checkout'])->name('klien.checkout');
    
    // PROSES BOOKING + MIDTRANS
    Route::post('/checkout', [c_payment::class, 'proses'])->name('klien.payment.proses');
    
    
    // ==========pembayaran klien ==========
    Route::get('/pembayaran', [c_payment::class, 'list'])->name('klien.pembayaran.list');
    Route::post('/pembayaran/proses', [c_payment::class, 'proses'])->name('klien.pembayaran.proses');
    Route::get('/pembayaran/bayar/{id}', [c_payment::class, 'bayar'])->name('klien.pembayaran.bayar');
    Route::get('/pembayaran/pelunasan/{id}', [c_payment::class, 'pelunasan'])->name('klien.pembayaran.pelunasan');
    Route::get('/pembayaran/sukses', [c_payment::class, 'sukses'])->name('klien.pembayaran.sukses');
    Route::get('/pembayaran/pending', [c_payment::class, 'pending'])->name('klien.pembayaran.pending');
    Route::post('/pembayaran/upload/{id}', [c_payment::class, 'uploadBukti'])->name('klien.pembayaran.upload');









});


/*
|--------------------------------------------------------------------------
| Kelola Yang ada di ADMIN
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->middleware('auth')->group(function () {

 // ========== Kelola Tanggal ==========
    Route::get('dates', [c_date::class, 'index'])->name('admin.dates.index');
    Route::post('dates/update-status', [c_date::class, 'updateStatus'])->name('admin.dates.updateStatus');


 // ========== Kelola Paket ==========
     Route::get('/package', [c_package::class, 'index'])->name('admin.package.index');
    Route::get('/package/create', [c_package::class, 'create'])->name('admin.package.create');
    Route::post('/package', [c_package::class, 'store'])->name('admin.package.store');
    Route::get('/package/{id}', [c_package::class, 'show'])->name('admin.package.show');
    Route::get('/package/{id}/edit', [c_package::class, 'edit'])->name('admin.package.edit');
    Route::put('/package/{id}', [c_package::class, 'update'])->name('admin.package.update');
    Route::delete('/package/{id}', [c_package::class, 'destroy'])->name('admin.package.destroy');
    Route::get('/vendorservices/approved', [c_package::class, 'vendorApprovedList'])->name('admin.vendorservices.approved');




 // ========== Kelola Kegiatan ==========
    Route::get('/event', [c_event::class, 'index'])->name('admin.event.index');
    Route::get('/event/create', [c_event::class, 'create'])->name('admin.event.create');
    Route::post('/event', [c_event::class, 'store'])->name('admin.event.store');
    Route::get('/event/{id}/edit', [c_event::class, 'edit'])->name('admin.event.edit');
    Route::put('/event/{id}', [c_event::class, 'update'])->name('admin.event.update');
    Route::get('/event/{id}/detail', [c_event::class, 'show'])->name('admin.event.show');
    Route::delete('/event/{id}', [c_event::class, 'destroy'])->name('admin.event.destroy');
    Route::post('/event/{id}/toggle-publish', [c_event::class, 'togglePublish'])->name('admin.event.togglePublish');

    // Penugasan Tim (bagian dari event, tapi ditulis eksplisit biar jelas)
    Route::post('event/assign-task', [c_event::class, 'assignTask'])->name('admin.event.assignTask');
    Route::post('event/remove-task', [c_event::class, 'removeTask'])->name('admin.event.removeTask');


    // ========== Kelola Vendor ==========
    Route::get('vendor', [c_vendor::class, 'index'])->name('admin.vendor.index');
    Route::get('vendor/detail/{id}', [c_vendor::class, 'show'])->name('admin.vendor.show');
    Route::delete('vendor/delete/{id}', [c_vendor::class, 'destroy'])->name('admin.vendor.destroy');
    Route::put('vendor/status/{id}', [c_vendor::class, 'toggleStatus'])->name('admin.vendor.toggleStatus');
    Route::get('vendor/{vendor_id}/services', [c_vendorServiceAdmin::class, 'index'])->name('admin.vendor.services');
    Route::put('vendor/services/{id}/approve', [c_vendorServiceAdmin::class, 'approve'])->name('admin.vendor.services.approve');
    Route::put('vendor/services/{id}/reject', [c_vendorServiceAdmin::class, 'reject'])->name('admin.vendor.services.reject');



    // ========== Kelola Pengguna ==========
    Route::get('pengguna', [c_pengguna::class, 'index'])->name('admin.pengguna.index');
    Route::get('pengguna/create', [c_pengguna::class, 'create'])->name('admin.pengguna.create');
    Route::post('pengguna/store', [c_pengguna::class, 'store'])->name('admin.pengguna.store');
    Route::get('pengguna/{id}/edit', [c_pengguna::class, 'edit'])->name('admin.pengguna.edit');
    Route::post('pengguna/{id}/update', [c_pengguna::class, 'update'])->name('admin.pengguna.update');
    Route::get('pengguna/{id}/delete', [c_pengguna::class, 'delete'])->name('admin.pengguna.delete');


    // ========== Kelola Invoice ==========
    Route::get('invoice', [c_invoice::class, 'index'])->name('admin.invoice.index');
    Route::get('invoice/create', [c_invoice::class, 'create'])->name('admin.invoice.create');
    Route::post('invoice/store', [c_invoice::class, 'store'])->name('admin.invoice.store');
    Route::get('/invoice/pdf/{id}', [c_invoice::class, 'print'])->name('admin.invoice.pdf');
    Route::get('/invoice/print/{id}', [c_invoice::class, 'print'])->name('admin.invoice.print');
    Route::delete('invoice/{id}', [c_invoice::class, 'destroy'])->name('admin.invoice.destroy');


    // ========== Kelola Keuangan ==========
    Route::get('/keuangan', [c_keuangan::class, 'index'])->name('admin.keuangan.index');
    Route::get('/keuangan/detail/{id}', [c_keuangan::class, 'show'])->name('admin.keuangan.detail');
    Route::get('/keuangan/tambah', [c_keuangan::class, 'create'])->name('admin.keuangan.tambah');
    Route::post('/keuangan/store', [c_keuangan::class, 'store'])->name('admin.keuangan.store');
    Route::get('/keuangan/export-excel', [c_keuangan::class, 'exportExcel'])->name('admin.keuangan.excel');
    Route::get('/keuangan/export-pdf', [c_keuangan::class, 'exportPdf'])->name('admin.keuangan.pdf');




    

    // ========== Kelola Pesanan==========
    Route::get('pesanan', [c_pesanan::class, 'index'])->name('admin.pesanan.index');
    Route::get('pesanan/{id}', [c_pesanan::class, 'show'])->name('admin.pesanan.show');
    Route::post('pesanan/verifikasi/{id}', [c_pesanan::class, 'verifikasiPembayaran'])->name('admin.pesanan.verifikasi');


    // ========== Kelola Keuangan==========
    Route::get('/keuangan', [c_keuangan::class, 'index'])->name('admin.keuangan.index');
    Route::get('/keuangan/create', [c_keuangan::class, 'create'])->name('admin.keuangan.create');
    Route::post('/keuangan/store', [c_keuangan::class, 'store'])->name('admin.keuangan.store');
    Route::get('/keuangan/{id}', [c_keuangan::class, 'show'])->name('admin.keuangan.show');
    Route::get('/grafik-keuangan', [c_keuangan::class, 'grafik'])->name('admin.keuangan.grafik');
    Route::get('/export-keuangan-excel', [c_keuangan::class, 'exportExcel'])->name('admin.keuangan.exportExcel');
    Route::get('/export-keuangan-pdf', [c_keuangan::class, 'exportPdf'])->name('admin.keuangan.exportPdf');








});













/*
|--------------------------------------------------------------------------
| Kelola Yang ada di VENDOR
|--------------------------------------------------------------------------
*/

Route::prefix('vendor')->middleware('auth')->group(function () {

    // ========== Kelola Jasa & produk ==========
    Route::get('vendorservice', [c_vendorservice::class, 'index'])->name('vendor.service.index');
    Route::get('vendorservice/create', [c_vendorservice::class, 'create'])->name('vendor.service.create');
    Route::post('vendorservice/store', [c_vendorservice::class, 'store'])->name('vendor.service.store');
    Route::get('vendorservice/detail/{id}', [c_vendorservice::class, 'show'])->name('vendor.service.show');
    Route::get('vendorservice/edit/{id}', [c_vendorservice::class, 'edit'])->name('vendor.service.edit');
    Route::put('vendorservice/update/{id}', [c_vendorservice::class, 'update'])->name('vendor.service.update');
    Route::delete('vendorservice/delete/{id}', [c_vendorservice::class, 'destroy'])->name('vendor.service.destroy');

     // ========== Profile Vendor ==========
    Route::get('profile', [c_vendor_profile::class, 'index'])->name('vendor.profile.index');
    Route::get('profile/create', [c_vendor_profile::class, 'create'])->name('vendor.profile.create');
    Route::post('profile/store', [c_vendor_profile::class, 'store'])->name('vendor.profile.store');
    Route::get('profile/edit', [c_vendor_profile::class, 'edit'])->name('vendor.profile.edit');
    Route::put('profile/update', [c_vendor_profile::class, 'update'])->name('vendor.profile.update');


    // ========== Pesanan di Vendor ==========
    Route::get('pesanan', [c_vendorBooking::class, 'index'])->name('vendor.pesanan');
    Route::get('pesanan/{id}', [c_vendorBooking::class, 'detail'])->name('vendor.pesanan.detail');
    Route::post('pesanan/{id}/status', [c_vendorBooking::class, 'updateStatus'])->name('vendor.pesanan.status');



});





