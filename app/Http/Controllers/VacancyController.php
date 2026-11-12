<?php

namespace App\Http\Controllers;

use App\Models\Vacancy;
use Illuminate\View\View;

class VacancyController extends Controller
{
    public function index(): View
    {
        $vacancies = Vacancy::orderByDesc('posted_at')->orderByDesc('id')->get();

        return view('vacancies.index', compact('vacancies'));
    }
}
