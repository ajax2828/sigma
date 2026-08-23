<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\QRCodeController;
use App\Http\Controllers\QRScanController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\SendCertificateViaEmail;

Route::get('/', function () {
    return view('maintenance');
});

// Route untuk login dan register
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'processLogin'])->name('login.process');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'processRegister'])->name('register.process');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Route untuk registrasi dan generate QR Code
Route::get('/registrasi/seminar', [RegistrationController::class, 'show'])->name('registrasi');
Route::post('/registrasi/seminar', [RegistrationController::class, 'regist'])->name('registrasi.Seminar');
Route::get('/scan/{code}', [RegistrationController::class, 'scan'])->name('registrants.scan');

Route::get('/email', function () {
    return view('emails.seminar');
});

Route::middleware('auth')->group(function () {
    Route::get('/control/panel/seminar/admin', [AdminController::class, 'show'])->name('admin');
    Route::get('/dashboard-data', [App\Http\Controllers\AdminController::class, 'getDashboardData'])->name('dashboard.data');
    Route::get('/search-registrants', [App\Http\Controllers\AdminController::class, 'searchRegistrants'])->name('search.registrants');

    Route::get('/registrasi/seminar/import', [RegistrationController::class, 'importShow'])->name('registrasi.Seminar.import.show');
    Route::post('/registrasi/seminar/import', [RegistrationController::class, 'import'])->name('registrasi.Seminar.import');

    Route::get('/check-email-status', [RegistrationController::class, 'checkEmailStatus'])->name('check.email.status');

    Route::get('/scan/depan', [QRScanController::class, 'show'])->name('scan');
    Route::get('/scan/belakang', [QRScanController::class, 'show'])->name('scan.back');
    Route::post('/scan-result', [QRScanController::class, 'scan']);

    Route::get('/send-certificate', [SendCertificateViaEmail::class, 'show'])->name('send.certificate');
    Route::post('/send-certificate', [SendCertificateViaEmail::class, 'send'])->name('send.certificate.process');
    Route::get('/send-certificate/email', [SendCertificateViaEmail::class, 'email'])->name('email.certificate');
    Route::get('/send-certificate/tes', [SendCertificateViaEmail::class, 'tes'])->name('tes.certificate');
});
