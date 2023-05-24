<?php

use App\Models\MessageAnalytic;
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


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware(['api.authenticate'])->group(function () {
    Route::post('/v1/message-insight', function (Request $request) {
        return MessageAnalytic::saveMessageAnalyticFromRequest($request);
    })->name('message-insight')->middleware('validate.message');

    Route::get('/v1/message-insight/{message}', function (MessageAnalytic $message) {
        return MessageAnalytic::getAnalyticMessage($message);
    });

    Route::post('/v1/message-insight/{id}/feedback', function (MessageAnalytic $id, Request $request) {
        return MessageAnalytic::saveUserFeedback($request, $id);
    })->name('message-insight.feedback')->middleware('validate.feedback');
});
