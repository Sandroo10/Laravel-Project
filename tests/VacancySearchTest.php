<?php

namespace Tests;

require_once __DIR__ . '/VacancyTestCase.php';

use App\Models\Vacancy;
use Database\Seeders\VacancySeeder;

class VacancySearchTest extends VacancyTestCase
{
    public function test_search_combines_position_and_company_matches_with_the_filter(): void
    {
        $this->seed(VacancySeeder::class);

        $this->get(route('vacancies.index', ['search' => 'Developer']))
            ->assertOk()
            ->assertSee('6 matching roles');

        $this->get(route('vacancies.index', ['search' => 'BluePeak', 'employment_type' => 'remote']))
            ->assertOk()
            ->assertSee('Technical Writer')
            ->assertDontSee('Frontend Developer')
            ->assertSee('1 matching role');

        $this->get(route('vacancies.index', ['search' => 'BluePeak', 'employment_type' => 'part-time']))
            ->assertOk()
            ->assertSee('No matching roles just yet.');
    }

    public function test_filter_works_without_a_search_term(): void
    {
        $this->seed(VacancySeeder::class);

        $this->get(route('vacancies.index', ['employment_type' => 'part-time']))
            ->assertOk()
            ->assertViewHas('vacancies', fn ($vacancies) => $vacancies->total() === 8
                && $vacancies->getCollection()->every(fn (Vacancy $vacancy) => $vacancy->employment_type === 'part-time'));
    }

    public function test_invalid_search_parameters_are_rejected(): void
    {
        $this->getJson(route('vacancies.index', ['search' => ['Developer'], 'employment_type' => 'unknown']))
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['search', 'employment_type']);
    }
}
