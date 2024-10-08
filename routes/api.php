<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\EventController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/register', [AuthController::class, 'register'])->name('register');

Route::middleware('auth:sanctum')->get('/user', [UserController::class, 'index'])->name('getUser');
Route::middleware('auth:sanctum')->put('/user/{id}', [UserController::class, 'update'])->name('updateUser');
Route::middleware('auth:sanctum')->delete('/user/{id}', [UserController::class, 'destroy'])->name('deleteUser');

Route::middleware('auth:sanctum')->post('/events', [EventController::class, 'store'])->name('postEvent');
Route::middleware('auth:sanctum')->get('/events', [EventController::class, 'index'])->name('getEvent');
Route::middleware('auth:sanctum')->put('/events/{id}', [EventController::class, 'update'])->name('updateEvent');
Route::middleware('auth:sanctum')->delete('/events/{id}', [EventController::class, 'destroy'])->name('deleteEvent');
