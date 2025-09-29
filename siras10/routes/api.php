<?php

use App\Http\Controllers\Admin\RoleController;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/roles/{role}/users', [RoleController::class, 'getUsersByRole']);

Route::get('/menus/{menu}/submenus', function (Menu $menu) {
    return response()->json($menu->submenus()->get());
});
