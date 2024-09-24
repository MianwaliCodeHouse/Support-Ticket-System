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
    Route::get('/tickets', [TicketsController::class, 'index'])->name('tickets.index');
    Route::post('/tickets/', [TicketsController::class, 'store'])->name('tickets.store');
    Route::delete('/tickets/{id}', [TicketsController::class, 'destroy'])->name('ticket.destroy');
    Route::post('/tickets/{id}', [TicketsController::class, 'update'])->name('ticket.update');
    Route::get('/tickets/data/{id?}', [TicketsController::class, 'data'])->name('tickets.data');
    Route::get('/ticket/accept/{id}', [TicketsController::class, 'accept'])->name('ticket.accept');

    // tickets details routes
    Route::get('/tickets/{id}/show', [TicketDetailsController::class, 'index'])->name('ticket.details');
    Route::post('/ticket/message/', [TicketDetailsController::class, 'store'])->name('ticket.message.store');
    Route::get('/ticket/close/{id}', [TicketDetailsController::class, 'close'])->name('ticket.close');
   

    // profile routes 
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
