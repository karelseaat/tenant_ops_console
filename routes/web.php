<?php

use App\Http\Controllers\TenantOpsDashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', [TenantOpsDashboardController::class, 'index']);
