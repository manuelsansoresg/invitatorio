<?php

use App\Http\Controllers\InvitationController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/{slug}', [InvitationController::class, 'show'])->name('invitation.show');
Route::post('/{slug}/rsvp', [InvitationController::class, 'rsvp'])->name('invitation.rsvp');
