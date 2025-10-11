<?php

use App\Http\Controllers\Profile;
use App\Http\Controllers\Project;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\Contacts;
use App\Http\Controllers\ExperienceController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::get('/profile', [Profile::class, 'index']);
Route::get('/projects', [Project::class, 'index']);
Route::get('/experiences', [ExperienceController::class, 'index']);
Route::post('/contact', [ContactController::class, 'store']);
Route::get('/contact', [Contacts::class, 'index']);
