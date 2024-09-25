<?php

use App\Http\Controllers\Dashboard\TicketDetailsController;
use App\Http\Controllers\Dashboard\TicketsController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {

    // tickets routes 
    Route::resource('tickets', TicketsController::class)->except('show');
    Route::get('/tickets/data/{id?}', [TicketsController::class, 'dataTable'])->name('tickets.data');
    Route::get('/ticket/accept/{id}', [TicketsController::class, 'accept'])->name('ticket.accept');


    // tickets details routes
    Route::resource('ticket-details', TicketDetailsController::class);
    Route::get('/ticket/close/{id}', [TicketDetailsController::class, 'close'])->name('ticket.close');
    
   

    // profile routes 
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
