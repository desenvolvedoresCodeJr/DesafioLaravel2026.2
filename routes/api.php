<?php

use App\Http\Controllers\CepController;
use Illuminate\Support\Facades\Route;

Route::get('/cep/{cep}', [CepController::class, 'show']);