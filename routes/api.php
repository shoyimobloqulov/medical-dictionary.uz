<?php

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/',[ApiController::class,'index']);
Route::get('/languages',[ApiController::class,'languages']);
Route::get('/dictionary',[ApiController::class,'dictionary']);

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
