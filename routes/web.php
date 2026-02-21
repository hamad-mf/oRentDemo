<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\DashboardController;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

use App\Http\Controllers\VehicleController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\InvestmentController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\ChallanController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\GpsController;

Route::resource('vehicles', VehicleController::class);
Route::resource('clients', ClientController::class);
Route::post('clients/{id}/rate', [ClientController::class, 'rate'])->name('clients.rate');
Route::post('clients/{id}/blacklist', [ClientController::class, 'blacklist'])->name('clients.blacklist');
Route::resource('reservations', ReservationController::class);
Route::get('reservations/{id}/deliver', [ReservationController::class, 'deliver'])->name('reservations.deliver');
Route::post('reservations/{id}/deliver', [ReservationController::class, 'storeDelivery'])->name('reservations.deliver.store');
Route::get('reservations/{id}/return', [ReservationController::class, 'returnVehicle'])->name('reservations.return');
Route::post('reservations/{id}/return', [ReservationController::class, 'storeReturn'])->name('reservations.return.store');
Route::resource('investments', InvestmentController::class);
Route::resource('documents', DocumentController::class);
Route::resource('expenses', ExpenseController::class);
Route::resource('challans', ChallanController::class);
Route::resource('staff', StaffController::class);
Route::get('gps', [GpsController::class, 'index'])->name('gps.index');
