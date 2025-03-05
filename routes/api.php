<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\apiadmincontroller;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

//So after Successully Dispatached and Delivered Successfully by the third party now change the order_status =3
//First Display the dispatched shipped product detail like name etc
Route::get('/delivery_detail', [apiadmincontroller::class, 'delivery_detail'])->name('delivery_detail');
//Now Change Status to 3 i.e Delivered Successfull
Route::post('/delivered_success', [apiadmincontroller::class, 'delivered_success'])->name('delivered_success');
