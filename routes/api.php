<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\BerandaAdminController;
use App\Http\Controllers\BerandaSuperAdminController;
use App\Http\Controllers\Masyarakat\PelaporanKeDinasController;
use App\Http\Controllers\Masyarakat\PelaporanKePolisiController;
use App\Models\PelaporanKeDinas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::post('login', [LoginController::class, 'loginApi']);
Route::resource('pelaporan-masyarakat-ke-dinas', PelaporanKeDinasController::class)->middleware('auth:sanctum');
Route::resource('pelaporan-masyarakat-ke-polisi', PelaporanKePolisiController::class)->middleware('auth:sanctum');


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
