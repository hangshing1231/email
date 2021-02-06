<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MailController;
use App\Http\Middleware\TokenValidation;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('send', [MailController::class, 'emailSend'])->middleware(TokenValidation::class);
Route::get('list', [MailController::class, 'list'])->middleware(TokenValidation::class);
Route::get('download/{id}', [MailController::class, 'download'])->middleware(TokenValidation::class);