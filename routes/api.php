<?php

    use App\Http\Controllers\AuthController;
    use App\Http\Controllers\TicketController;
    use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function() {
    Route::post('register', [AuthController::class, 'register']);
});

Route::middleware('auth:sanctum')->prefix('ticket')->group(function() {
    Route::get('list', [TicketController::class, 'list']);
    Route::get('view/{ticket_id}', [TicketController::class, 'view']);

    Route::post('create', [TicketController::class, 'create']);
    Route::post('reply', [TicketController::class, 'reply']);
});

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
