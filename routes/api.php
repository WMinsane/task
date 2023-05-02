<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\TaskListController;
use App\Http\Controllers\API\ProjListController;
use App\Http\Controllers\API\ProjDetailController;
use App\Http\Controllers\API\TaskDetailController;

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
Route::post('/getTaskList', [TaskListController::class,'getTaskList']);
Route::get('/getProjItems', [TaskListController::class,'getProjItems']);
Route::get('/getProjList', [ProjListController::class,'getProjList']);
Route::post('/getProjDetail', [ProjDetailController::class,'getProjDetail']);
Route::get('/getProjStatusList', [ProjDetailController::class,'getProjStatusList']);
Route::post('/updateProjDetail', [ProjDetailController::class,'updateProjDetail']);
Route::post('/createProject', [ProjDetailController::class,'createProject']);
Route::post('/getTaskDetail', [TaskDetailController::class,'getTaskDetail']);
Route::post('/updateTaskDetail', [TaskDetailController::class,'updateTaskDetail']);
Route::post('/createTask', [TaskDetailController::class,'createTask']);
