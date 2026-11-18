<?php

namespace Tests;

require_once __DIR__ . '/VacancyTestCase.php';

use App\Models\Vacancy;

class VacancyCrudTest extends VacancyTestCase
{
    public function test_vacancies_can_be_created_edited_and_deleted(): void
    {
        $data = $this->vacancyData() + ['position' => 'PHP Developer'];

        $this->post(route('vacancies.store'), $data)
            ->assertRedirect(route('vacancies.index'))
            ->assertSessionHas('success');

        $vacancy = Vacancy::sole();

        $this->get(route('vacancies.edit', $vacancy))
            ->assertOk()
            ->assertSee('value="PHP Developer"', false);

        $this->put(route('vacancies.update', $vacancy), array_replace($data, ['city' => 'Batumi']))
            ->assertRedirect(route('vacancies.index'))
            ->assertSessionHas('success');
        $this->assertDatabaseHas('vacancies', ['id' => $vacancy->id, 'city' => 'Batumi']);

        $this->delete(route('vacancies.destroy', $vacancy))
            ->assertRedirect(route('vacancies.index'))
            ->assertSessionHas('success');
        $this->assertModelMissing($vacancy);
    }

    public function test_edit_update_and_delete_return_not_found_for_missing_vacancies(): void
    {
        $this->get(route('vacancies.edit', 999))->assertNotFound();
        $this->put(route('vacancies.update', 999))->assertNotFound();
        $this->delete(route('vacancies.destroy', 999))->assertNotFound();
    }
}
