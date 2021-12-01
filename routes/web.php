<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ProjectAccessController;
use App\Http\Controllers\KanbanController;

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

// Route::get('/', function () {
//     return view('welcome');
// });
Route::get('/', [App\Http\Controllers\Auth\LoginController::class, 'showLoginForm']);

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::group(['middleware' => ['auth']], function() {
    Route::resource('roles', RoleController::class);
    Route::resource('users', UserController::class);
    Route::resource('products', ProductController::class);
    Route::resource('project', ProjectController::class);
    Route::resource('project_access', ProjectAccessController::class);

    // Kanban routes
    Route::resource('kanban', KanbanController::class);
    Route::post('/changeStatus', [KanbanController::class, 'updateStatus'])->name('updateStatus');

    Route::get('/getProject', [ProjectController::class, 'getProject'])->name('getProject');
    Route::get('/getUsers', [UserController::class, 'getUsers'])->name('getUsers');
    Route::get('/roleList', [RoleController::class, 'roleList'])->name('roleList');
});

// Route::get('/test', function() {
//     return view('layouts');
// });