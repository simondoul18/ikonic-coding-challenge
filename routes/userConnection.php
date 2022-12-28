<?php

use Illuminate\Support\Facades\Route;


Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/sent-requests', [App\Http\Controllers\HomeController::class, 'index']);
Route::get('/received-requests', [App\Http\Controllers\HomeController::class, 'index']);
Route::get('/connections', [App\Http\Controllers\HomeController::class, 'index']);

// Suggestions
Route::get('/get-suggestions', [App\Http\Controllers\HomeController::class, 'getSuggestions']);
Route::get('/get-counts', [App\Http\Controllers\HomeController::class, 'getCounts']);
Route::post('send_friend_request', [App\Http\Controllers\HomeController::class, 'sendRequest']);

// Requests
Route::get('get-requests/{mode}', [App\Http\Controllers\HomeController::class, 'getRequests']);
Route::post('delete-request', [App\Http\Controllers\HomeController::class, 'deleteRequest']);
Route::post('accept-request', [App\Http\Controllers\HomeController::class, 'acceptRequest']);

// Connections
Route::get('get-connections', [App\Http\Controllers\HomeController::class, 'getConnections']);
Route::post('remove-connection', [App\Http\Controllers\HomeController::class, 'removeConnection']);
