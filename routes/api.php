<?php

use App\Http\Controllers\ChatController;
use App\Http\Controllers\CommentController;
use Illuminate\Support\Facades\Route;

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

Route::group(["middleware" => 'ajax', "prefix" => 'listings/{listing:slug}', "as" => "listings.comments."], function () {
    Route::post('comment', [CommentController::class, 'store'])->name('post');
});
Route::group(["middleware" => 'auth', "prefix" => 'chat', "as" => "chat."], function () {
    Route::get('room/{room:id}/messages', [ChatController::class, 'show']);
    Route::post('room/message', [ChatController::class, 'store']);
    Route::get('rooms', [ChatController::class, 'index'])->name('rooms');
    Route::post('rooms', [ChatController::class, 'findOrNew',]);
});
