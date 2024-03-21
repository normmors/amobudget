<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AmoCrmHookController;

Route::get('/', [AmoCrmHookController::class, 'handle']);