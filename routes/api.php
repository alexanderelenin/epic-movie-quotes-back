<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\EmailController;
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\QuoteController;
use App\Http\Controllers\PasswordController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SecondaryEmailController;
use App\Http\Requests\SecondEmailRequest;
use GuzzleHttp\Middleware;
use Illuminate\Http\Request;
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

Route::controller(AuthController::class)->group(function () {
    Route::post('register', 'register');
    Route::post('login', 'login');
    Route::get('me', 'me')->middleware('jwt.auth');
    Route::get('user', 'user');
    Route::post('logout', 'logout');
});



Route::controller(EmailController::class)->group(function () {
    Route::get('/email/verify/{id}/{hash}', 'verify')->middleware(['verification', 'signed'])->name('verification.verify');
});

Route::controller(GoogleController::class)->group(function () {
    Route::get('redirect', 'redirectToProvider')->name('login');
    Route::get('callback', 'handleProviderCallback')->name('callback');
});
Route::controller(PasswordController::class)->group(function () {

    Route::post('forgot-password', 'validateEmail')->middleware('guest')->name('password.email');
    Route::get('/reset-password/{token}', 'reset')->middleware('guest')->name('password.reset');
    Route::post('/reset-password', 'update')->middleware('guest')->name('password.update');
});

Route::controller(MovieController::class)->middleware('jwt.auth')->group(function () {
    Route::post('store-movie', 'store')->name('movie.store');
    Route::get('movies', 'index')->name('movies');
    Route::get('movie/{movie}', 'get')->name('movie.single');
    Route::post('movie/{movie}', 'update')->name('movie.update');
    Route::delete('movie-delete/{movie}', 'destroy')->name('movie.delete');
});

Route::controller(QuoteController::class)->middleware('jwt.auth')->group(function () {
    Route::post('store-quote', 'store')->name('quote.store');
    Route::post('delete-quote/{quote}', 'destroy')->name('quote.delete');
    Route::get('quote/{quote:id}', 'get')->name('quote.single');
    Route::post('quote/{quote}', 'update')->name('quote.update');
    Route::get('quotes', 'index')->name('quote.all');
    Route::post('quote-search', 'search')->name('quote.search');
    Route::post('refresh', 'refresh');
});

Route::controller(CommentController::class)->middleware('jwt.auth')->group(function () {
    Route::post('quotes/{quote:id}/comments', 'store')->name('comment.create');
});

Route::controller(LikeController::class)->middleware('jwt.auth')->group(function () {
    Route::post('quotes/{quote:id}', 'like')->name('quote.like');
});

Route::controller(NotificationController::class)->middleware('jwt.auth')->group(function () {
    Route::get('notifications', 'get')->name('notifications.get');
    Route::get('unread', 'unread')->name('notifications.unread');
    Route::post('mark', 'mark')->name('notification.mark');
});

Route::controller(UserController::class)->middleware('jwt.auth')->group(function () {
    Route::post('user/update', 'update')->name('users.update');
});

Route::controller(SecondaryEmailController::class)->middleware('jwt.auth')->group(function () {
    Route::post('email/add', 'store')->name('email.add');
    Route::delete('email/{email}', 'destroy')->name('email.delete');
    Route::post('email-verify', 'verify')->name('email.verify');
    Route::post('email/update', 'change')->name('email.update');
});
