<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Controller;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
Route::get("/getticker",[Controller::class,'GetTicker'])->name("get.ticker");
Route::get("/get24hticker",[Controller::class,'Get24HTicker'])->name("get.24h.ticker");
Route::get("/getbestticker",[Controller::class,'GetBestPrice'])->name("get.best.price");

Route::get("/getorderbook",[Controller::class,'GetOrderBook'])->name("get.btc");//testing
