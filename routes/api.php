<?php

use App\Http\Controllers\CareerMapController;
Route::get('/career-map/{id}', [CareerMapController::class,'apiShow']);
