<?php

use App\Http\Controllers\CommentController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use GuzzleHttp\Middleware;
use App\Http\Controllers\Auth\OAuthController;

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

// /にアクセスした場合、welcome.blade.phpが表示されるのルーティング変更
// - Route::get('/', function () {
// -   return view('welcome');
// - });
Route::get('/', [PostController::class, 'index'])
    ->name('root');



Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');


// PostController設定(↑use App\Http\Controllers\PostController;つける）
// 認証機能設定
Route::resource('posts', PostController::class)
    ->only(['create', 'store', 'edit', 'update', 'destroy'])
    ->middleware('auth');
// 非認証機能設定
Route::resource('posts', PostController::class)
    ->only(['show', 'index']);

// Commenet ルーティング設定
Route::resource('posts.comments', CommentController::class)
    ->only(['create', 'store', 'edit', 'update', 'destroy'])
    ->middleware('auth');

require __DIR__ . '/auth.php';

// authから始まるルーティングに認証前にアクセスがあった場合
Route::prefix('auth')->middleware('guest')->group(function () {
    // auth/githubにアクセスがあった場合はOAuthControllerのredirectToProviderアクションへルーティング
    Route::get('/github', [OAuthController::class, 'redirectToProvider'])
        ->name('redirectToProvider');

    // auth/github/callbackにアクセスがあった場合はOAuthControllerのoauthCallbackアクションへルーティング
    Route::get('/github/callback', [OAuthController::class, 'oauthCallback'])
        ->name('oauthCallback');
});
