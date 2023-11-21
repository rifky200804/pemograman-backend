<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\AuthController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});



Route::post('register',[AuthController::class,'register'])->name('regisetr');
Route::post('login',[AuthController::class,'login'])->name('login');


Route::group(['middleware' => ['auth:sanctum']], function(){
    Route::group(['prefix'=>'patients','as'=>'patients.'],function(){
        Route::get('/',[PatientController::class,'index'])->name('index');
        Route::get('/{id}',[PatientController::class,'show'])->name('show');
        Route::post('/',[PatientController::class,'store'])->name('store');
        Route::put('/{id}',[PatientController::class,'update'])->name('update');
        Route::delete('/{id}',[PatientController::class,'destroy'])->name('destroy');
    });

    
    Route::post('logout',[AuthController::class,'logout'])->name('logout');
});

Route::get('/test',function(){
    return response()->json(["success auth"]);
})->middleware('auth:sanctum');
