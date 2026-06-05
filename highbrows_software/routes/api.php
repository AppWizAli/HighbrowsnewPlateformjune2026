<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AdmissionController;
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
Route::get('/admission', function () {
    return ("hello");
});
Route::post('/admissions', [AdmissionController::class, 'store']);
Route::get('/admissions',[AdmissionController::class,'fetchAll']);
Route::get('/admissions/{id}',[AdmissionController::class,'getSingleUser']);
Route::delete('/admissions/{id}',[AdmissionController::class,'destroy']);
// Route::get('/admissions/{id}/edit',[AdmissionController::class,'edit']);
// yar mjy lg rha h code mai koi issue nhi h ye postman sy data insert krny mai error aa rha wo image upload nhi na ho rhi 
// Route::put('/admissions/update/{id}',[AdmissionController::class,'update']);
Route::put('/admissions/abc/{id}',[AdmissionController::class,'updateForm']);