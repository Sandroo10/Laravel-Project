<?php

namespace App\Http\Controllers;

use App\Models\Vacancy;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class VacancyController extends Controller
{
    public function index(Request $request): View
    {
        $validated = $request->validate([
            'search' => ['nullable', 'string', 'max:255'],
        ]);
        $search = trim($validated['search'] ?? '');
        $query = Vacancy::query();

        if ($search !== '') {
            $query->where(function ($query) use ($search) {
                $query->where('position', 'like', '%'.$search.'%')
                    ->orWhere('company', 'like', '%'.$search.'%');
            });
        }

        $vacancies = $query->orderByDesc('posted_at')->orderByDesc('id')->get();

        return view('vacancies.index', compact('vacancies', 'search'));
    }

    public function create(): View
    {
        return view('vacancies.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'position' => ['required', 'string', 'max:255'],
            'company' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:255'],
            'salary_from' => ['required', 'integer', 'min:0', 'max:2147483647'],
            'employment_type' => ['required', Rule::in(['full-time', 'part-time', 'remote'])],
            'posted_at' => ['required', 'date_format:Y-m-d', 'after_or_equal:1000-01-01', 'before_or_equal:9999-12-31'],
        ]);

        Vacancy::create($validated);

        return redirect()->route('vacancies.index')->with('success', 'Vacancy posted successfully.');
    }

    public function edit(Vacancy $vacancy): View
    {
        return view('vacancies.edit', compact('vacancy'));
    }

    public function update(Request $request, Vacancy $vacancy): RedirectResponse
    {
        $validated = $request->validate([
            'position' => ['required', 'string', 'max:255'],
            'company' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:255'],
            'salary_from' => ['required', 'integer', 'min:0', 'max:2147483647'],
            'employment_type' => ['required', Rule::in(['full-time', 'part-time', 'remote'])],
            'posted_at' => ['required', 'date_format:Y-m-d', 'after_or_equal:1000-01-01', 'before_or_equal:9999-12-31'],
        ]);

        $vacancy->update($validated);

        return redirect()->route('vacancies.index')->with('success', 'Vacancy updated successfully.');
    }

    public function destroy(Vacancy $vacancy): RedirectResponse
    {
        $vacancy->delete();

        return redirect()->route('vacancies.index')->with('success', 'Vacancy deleted successfully.');
    }
}
