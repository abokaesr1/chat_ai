<?php

use Illuminate\Support\Facades\Route;
use Salamat\chat_ai\Http\Controllers\ChatAiController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/chat', function () {
    return View('Salamat/chat_ai::chat_ai');
});


Route::post('/generatetext', [ChatAiController::class, 'generateText']);
