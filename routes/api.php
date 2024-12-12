<?php
use App\Http\Controllers\ContactController;
use Illuminate\Support\Facades\Route;

Route::get('contacts', [ContactController::class, 'index']);
Route::post('contacts', [ContactController::class, 'store']);
Route::put('contacts/{id}', [ContactController::class, 'update']);
Route::delete('contacts/{id}', [ContactController::class, 'destroy']);
