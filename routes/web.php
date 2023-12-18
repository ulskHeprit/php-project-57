<?php

use App\Http\Controllers\LabelController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TaskStatusController;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('index');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    //Task statuses
    Route::resource('/task_statuses', TaskStatusController::class)->except('index');

    //Labels
    Route::resource('/labels', LabelController::class)->except('index');

    //Tasks
    Route::resource('/tasks', TaskController::class)->except(['index', 'show']);
});

//Task statuses
Route::resource('/task_statuses', TaskStatusController::class)->only('index');

//Labels
Route::resource('/labels', LabelController::class)->only('index');

//Tasks
Route::resource('/tasks', TaskController::class)->only(['index', 'show']);

require __DIR__.'/auth.php';
