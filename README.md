# RoleBoard

A job vacancy board developed for the Kutaisi International University Laravel MVC midterm, Topic N8.

**Author:** Sandro Saralidze

RoleBoard helps users browse and manage vacancies through a responsive interface. Each vacancy includes a position, company, city, starting salary, employment type, and posting date.

## Features

- Create, view, edit, and delete vacancies, with confirmation before deletion.
- Search by position or company.
- Filter by Full-time, Part-time, or Remote, together with search.
- Browse 10 vacancies per page while keeping search and filter choices.
- Form validation, retained input, and success messages.
- 24 sample vacancies for demonstration.

## Technologies

Laravel 12, PHP 8.2+, MySQL 8, Eloquent ORM, Blade, Tailwind CSS, Vite, and vanilla JavaScript.

The application follows Laravel's MVC structure, with shared Blade layouts and component-based CSS. It does not require user registration or login.

## Installation

Requires PHP, Composer, MySQL, and Node.js/npm for building frontend assets.

```sh
composer install
cp .env.example .env
php artisan key:generate
```

Create a MySQL database named `vacancies_db`. Set your database username and password in `.env`.

```sh
php artisan migrate
php artisan db:seed --class=VacancySeeder
npm ci
npm run build
php artisan serve --no-reload
```

Open [http://127.0.0.1:8000](http://127.0.0.1:8000) in your browser.

The frontend runs using compiled assets, so `npm run dev` is not required. If `public/build` is already included in the submission, the npm commands can be skipped.

For a fresh database rebuild, run `php artisan migrate:fresh`, followed by `php artisan db:seed --class=VacancySeeder`. Rebuilding deletes existing tables and their data.

## Database

Database: `vacancies_db`. Table: `vacancies`.

| Column | Type |
| --- | --- |
| id | bigint, primary key |
| position | string |
| company | string |
| city | string |
| salary_from | integer |
| employment_type | string: full-time, part-time, remote |
| posted_at | date |
| created_at | timestamp |
| updated_at | timestamp |

## Screenshots

[Vacancy listing](screenshots/vacancy-listing.jpg) · [Search results](screenshots/search-results.jpg) · [Create form](screenshots/create-form.jpg) · [Edit form](screenshots/edit-form.jpg)
