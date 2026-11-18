<?php

namespace Tests;

require_once __DIR__ . '/VacancyTestCase.php';

use App\Models\Vacancy;

class VacancyValidationTest extends VacancyTestCase
{
    public function test_invalid_data_is_rejected_and_other_input_is_retained(): void
    {
        $data = array_replace($this->vacancyData(), ['position' => '', 'salary_from' => -1]);

        $this->from(route('vacancies.create'))->post(route('vacancies.store'), $data)
            ->assertRedirect(route('vacancies.create'))
            ->assertSessionHasErrors(['position', 'salary_from'])
            ->assertSessionHasInput('company', 'Example Studio');

        $this->assertDatabaseCount('vacancies', 0);
    }

    public function test_invalid_edit_does_not_overwrite_existing_data(): void
    {
        $vacancy = Vacancy::create($this->vacancyData() + ['position' => 'PHP Developer']);
        $editUrl = route('vacancies.edit', $vacancy);
        $data = array_replace($this->vacancyData(), [
            'position' => str_repeat('a', 256),
            'city' => 'Batumi',
            'employment_type' => 'unknown',
            'posted_at' => '2026-02-30',
        ]);

        $this->from($editUrl)->put(route('vacancies.update', $vacancy), $data)
            ->assertRedirect($editUrl)
            ->assertSessionHasErrors(['position', 'employment_type', 'posted_at'])
            ->assertSessionHasInput('city', 'Batumi');

        $this->assertDatabaseHas('vacancies', [
            'id' => $vacancy->id,
            'position' => 'PHP Developer',
            'city' => 'Kutaisi',
        ]);
    }
}
