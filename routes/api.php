<?php
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ApiIntegrationController;


Route::get('/external-api', [ApiIntegrationController::class, 'fetchData']);
