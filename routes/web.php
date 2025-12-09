<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\QuizController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GemsController;
use App\Http\Controllers\ResultController;
use App\Http\Controllers\CosmeticController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\WheelSpinController;

// routes/web.php
Route::post('/gems/add', [GemsController::class, 'addGems'])->middleware('auth')->name('gems.add');

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth'])
    ->name('dashboard');


Route::get('/67', function () {
    return view('67');
})->middleware(['auth', 'verified'])->name('67');

Route::middleware('auth')->group(function () {
    //quizzes
    Route::get('/quizzes/{chapter?}', [QuizController::class, 'list'])->name('quizzes.list');
    Route::get('/quiz/{slug}', [QuizController::class, 'show'])->name('quiz.show');
    // resultaten worden afgehandeld via ResultController-routes hieronder
    Route::post('/quiz/complete/{slug}', [QuizController::class, 'complete'])
    ->name('quiz.complete')
    ->middleware('auth');


    //profiel
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // uiterlijk/cosmetics
    Route::get('/cosmetics', [CosmeticController::class, 'show'])->name('cosmetics.show');
    Route::post('/cosmetics', [CosmeticController::class, 'update'])->name('cosmetics.update');
    Route::post('/cosmetics/upload-avatar', [CosmeticController::class, 'uploadCustomAvatar'])->name('cosmetics.uploadAvatar');
});

Route::middleware('auth')->group(function () {
    Route::get('/results', [ResultController::class, 'index'])->name('results.index');
    Route::get('/results/ajax', [ResultController::class, 'ajaxIndex'])->name('results.ajax.index');
    Route::get('/results/{id}', [ResultController::class, 'show'])->name('results.show');
    Route::post('/results', [ResultController::class, 'store'])->name('results.store');
    Route::patch('/results/{id}/visibility', [ResultController::class, 'updateVisibility'])->name('results.visibility');
    Route::post('/results/{id}/share', [ResultController::class, 'share'])->name('results.share');
    Route::delete('/results/{id}/unshare/{userId}', [ResultController::class, 'unshare'])->name('results.unshare');
    Route::get('/users/search', [ResultController::class, 'searchUsers'])->name('users.search');
});

Route::middleware('auth')->group(function () {
    Route::post('/spins', [WheelSpinController::class, 'store'])->name('spins.store');
    Route::get('/spins/recent', [WheelSpinController::class, 'recent'])->name('spins.recent');
});

Route::get('/wheelspin', function () {
    return view('wheelspin');
})->middleware(['auth', 'verified'])->name('wheelspin');

require __DIR__.'/auth.php';
