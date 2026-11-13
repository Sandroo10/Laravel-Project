<?php

use App\Http\Controllers\VacancyController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/vacancies');

Route::resource('vacancies', VacancyController::class)->except(['show']);
