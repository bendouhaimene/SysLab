<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\PatientLoginController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\TestController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Reception\PatientController;
use App\Http\Controllers\Reception\InvoiceController;
use App\Http\Controllers\Biologist\ResultController;
use App\Http\Controllers\Doctor\ValidationController;
use App\Http\Controllers\Patient\PatientPortalController;

// Auth
Route::get('/',         [LoginController::class, 'showLogin'])->name('login');
Route::post('/login',   [LoginController::class, 'login'])->name('login.post');
Route::post('/logout',  [LoginController::class, 'logout'])->name('logout');

// Patient Auth
Route::get('/patient/login',        [PatientLoginController::class, 'showLogin'])->name('patient.login');
Route::post('/patient/login',       [PatientLoginController::class, 'login'])->name('patient.login.post');
Route::post('/patient/logout',      [PatientLoginController::class, 'logout'])->name('patient.logout');

// Admin
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard',        [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('users',         UserController::class);
    Route::resource('tests', TestController::class)->except(['show']);
    Route::get('/reports',           [ReportController::class, 'index'])->name('reports');
    Route::get('/payments',          [ReportController::class, 'payments'])->name('payments');
    Route::post('/tests/{test}/archive', [TestController::class, 'archive'])->name('tests.archive');
    Route::post('/tests/{test}/restore', [TestController::class, 'restore'])->name('tests.restore');
    Route::delete('/tests/{test}',       [TestController::class, 'destroy'])->name('tests.destroy');
});

// Receptionist
Route::middleware(['auth', 'role:receptionist'])->prefix('reception')->name('reception.')->group(function () {
    Route::get('/search',            [PatientController::class, 'search'])->name('search');
    Route::post('/search',           [PatientController::class, 'searchPost'])->name('search.post');
    Route::get('/patients/create',   [PatientController::class, 'create'])->name('patients.create');
    Route::post('/patients',         [PatientController::class, 'store'])->name('patients.store');
    Route::get('/invoice/create/{patient}', [InvoiceController::class, 'create'])->name('invoice.create');
    Route::post('/invoice',          [InvoiceController::class, 'store'])->name('invoice.store');
    Route::get('/invoice/{invoice}/print', [InvoiceController::class, 'printInvoice'])->name('invoice.print');
    Route::get('/invoices',          [InvoiceController::class, 'index'])->name('invoices');
});

// Biologist
Route::middleware(['auth', 'role:biologist'])->prefix('biologist')->name('biologist.')->group(function () {
    Route::get('/queue',             [ResultController::class, 'queue'])->name('queue');
    Route::get('/done',              [ResultController::class, 'done'])->name('done');
    Route::get('/invoice/{invoice}', [ResultController::class, 'show'])->name('show');
    Route::post('/results/{invoice}',[ResultController::class, 'store'])->name('store');
});

// Doctor
Route::middleware(['auth', 'role:doctor'])->prefix('doctor')->name('doctor.')->group(function () {
    Route::get('/pending',           [ValidationController::class, 'pending'])->name('pending');
    Route::get('/validated',         [ValidationController::class, 'validated'])->name('validated');
    Route::get('/invoice/{invoice}', [ValidationController::class, 'show'])->name('show');
    Route::post('/validate/{invoice}',[ValidationController::class, 'validate'])->name('validate');
    Route::post('/reject/{invoice}', [ValidationController::class, 'reject'])->name('reject');
});

// Patient Portal
Route::middleware(['patient.auth'])->prefix('patient')->name('patient.')->group(function () {
    Route::get('/results',           [PatientPortalController::class, 'results'])->name('results');
    Route::get('/results/pdf',       [PatientPortalController::class, 'downloadPdf'])->name('results.pdf');
});