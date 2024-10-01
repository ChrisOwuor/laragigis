<?php

use App\Http\Controllers\AuthorController;
use App\Http\Controllers\ListingController;
use App\Http\Controllers\StudentsController;
use App\Http\Middleware\TokenIsValid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/profile/', function (Request $request) {
    // Parse JSON data from request body
    $data = $request->json()->all(); // Retrieve all JSON data as an array

    // Example: Log the JSON data
    error_log('Request JSON data: ' . json_encode($data));

    // Handle further processing based on $data

    return response()->json(["msg" => "success"], 200);
});

Route::get('/', [ListingController::class, 'index']);
Route::get('/listings/{id}', [ListingController::class, 'show']);
Route::get('/students', [StudentsController::class, 'index']);
Route::get("/about/{id}", [StudentsController::class, 'show']);
