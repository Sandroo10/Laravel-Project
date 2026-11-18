<?php

namespace Tests;

require_once __DIR__ . '/VacancyTestCase.php';

use App\Models\Vacancy;

class VacancyListingTest extends VacancyTestCase
{
    public function test_paginated_results_preserve_search_and_filter(): void
    {
        for ($index = 1; $index <= 11; $index++) {
            Vacancy::create($this->vacancyData() + ['position' => "PHP Developer {$index}"]);
        }

        $query = ['search' => 'Developer', 'employment_type' => 'remote'];

        $this->get(route('vacancies.index', $query))
            ->assertOk()
            ->assertViewHas('vacancies', function ($vacancies) use ($query) {
                parse_str(parse_url($vacancies->nextPageUrl(), PHP_URL_QUERY), $nextPage);

                return $vacancies->count() === 10
                    && $nextPage === $query + ['page' => '2'];
            });

        $this->get(route('vacancies.index', $query + ['page' => 2]))
            ->assertOk()
            ->assertSee('PHP Developer 1')
            ->assertDontSee('PHP Developer 11');
    }

    public function test_home_redirects_to_the_vacancy_listing(): void
    {
        $this->get('/')->assertRedirect(route('vacancies.index'));
    }

    public function test_empty_listing_offers_to_create_a_vacancy(): void
    {
        $this->get(route('vacancies.index'))
            ->assertOk()
            ->assertSee('No vacancies have been posted yet.')
            ->assertSee('Post the first vacancy');
    }

    public function test_out_of_range_page_offers_a_link_back_to_results(): void
    {
        Vacancy::create($this->vacancyData() + ['position' => 'PHP Developer']);

        $query = ['search' => 'Developer', 'employment_type' => 'remote'];

        $this->get(route('vacancies.index', $query + ['page' => 99]))
            ->assertOk()
            ->assertSee('This page has no roles.')
            ->assertSee('href="'.e(route('vacancies.index', $query + ['page' => 1])).'"', false);
    }

    public function test_vacancy_text_is_escaped_in_the_listing(): void
    {
        $position = '<strong>PHP Developer</strong>';
        Vacancy::create($this->vacancyData() + ['position' => $position]);

        $this->get(route('vacancies.index'))
            ->assertOk()
            ->assertSee($position)
            ->assertDontSee($position, false);
    }
}
