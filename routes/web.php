<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;

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

require __DIR__ . '/auth.php';
