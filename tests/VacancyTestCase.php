<?php

namespace Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase;

abstract class VacancyTestCase extends TestCase
{
    use RefreshDatabase;

    public function createApplication()
    {
        $app = parent::createApplication();

        if (config('database.default') !== 'sqlite'
            || config('database.connections.sqlite.database') !== ':memory:'
            || config('database.connections.sqlite.url')) {
            throw new \RuntimeException('Vacancy tests require in-memory SQLite.');
        }

        return $app;
    }













    protected function vacancyData(): array
    {
        return [
            'company' => 'Example Studio',
            'city' => 'Kutaisi',
            'salary_from' => 2500,
            'employment_type' => 'remote',
            'posted_at' => '2026-11-18',
        ];
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
    }
}
