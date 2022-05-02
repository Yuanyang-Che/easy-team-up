<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\InvitationController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::middleware(['auth'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout');
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');

    //Events
    Route::get('/events', [EventController::class, 'index'])->name('event.index');

    Route::get('/events/new', [EventController::class, 'create'])->name('event.create');
    Route::post('/events/new', [EventController::class, 'store'])->name('event.store');

    Route::get('/events/{id}', [EventController::class, 'show'])->name('event.show');

    Route::get('/events/{id}/edit', [EventController::class, 'edit'])->name('event.edit');
    Route::post('/events/{id}/edit', [EventController::class, 'update'])->name('event.update');

    Route::post('/events/{id}/delete', [EventController::class, 'delete'])->name('event.delete');
    //End Events

    //Favorites
    Route::get('/favorites', [FavoriteController::class, 'index'])->name('favorite.index');
    Route::post('/favorites/{id}', [FavoriteController::class, 'update'])->name('favorite.update');
    // Route::post('/favorites/{id}/delete', [FavoriteController::class, 'index'])->name('favorite.delete');
    //End Favorites

    //Comments
    Route::post('/comments', [CommentController::class, 'create'])->name('comment.create');
    Route::post('/comments/{id}/edit', [CommentController::class, 'update'])->name('comment.update');
    Route::post('/comments/{id}/delete', [CommentController::class, 'delete'])->name('comment.delete');
    //End Comments

    Route::get('/invitation', [InvitationController::class, 'inviteForm'])->name('invitation.form');
    Route::post('/invitation/send', [InvitationController::class, 'sendOutInvitation'])->name('invitation.send');

    Route::get('/invitation/{id}/request', [InvitationController::class, 'requestForInvitation'])->name('invitation.request');
});

Route::get('/', function () {
    return redirect()->route('profile.index');
});

Route::get('/login', [AuthController::class, 'loginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('auth.login');

Route::get('/signup', [AuthController::class, 'signupForm'])->name('auth.signupForm');
Route::post('/signup', [AuthController::class, 'signup'])->name('auth.signup');


if (env('APP_ENV') !== 'local') {
    URL::forceScheme('https');
}
