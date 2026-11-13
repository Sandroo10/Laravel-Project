# RoleBoard

KIU Laravel MVC midterm, Topic N8: Job Vacancy Board.

## Current milestone

Laravel 12 configured for MySQL database `vacancies_db`, with a Vacancy model, a vacancies migration, and 24 demonstration vacancies. Sessions and cache use files; queues run synchronously, so framework storage needs no database tables. The public vacancy listing now displays the seeded records through an Eloquent controller and shared Blade layout. Create, edit and delete workflows are implemented with validation, confirmation and success feedback. Position/company search is implemented. Employment filtering and pagination remain for later milestones.

Project instructions supplied by the owner are preserved in `AGENTS.md`. The two supplied planning documents informed this setup; the original lecturer rubric and lecture files were not present in the workspace.

## Requirements

- PHP 8.2+ with Composer 2 and Laravel's required extensions, including `pdo_mysql`.
- MySQL 8 and a local database account with access to `vacancies_db`.
- Node.js 22.12+ (or 20.19+) and npm for Tailwind/Vite assets. Local verification used Node.js 24.11.1.

Composer resolves dependencies against PHP 8.2.0 for compatibility. The lock file is included for repeatable installations.

## Local setup

```powershell
composer install
Copy-Item .env.example .env
php artisan key:generate
npm ci
npm run build
```

Copy the environment file only on a fresh installation; do not overwrite an existing `.env`. Set `DB_USERNAME` and `DB_PASSWORD` in `.env` to your local MySQL account. The example username `roleboard` is a placeholder, not a provisioned account. Create an empty `vacancies_db` database with `utf8mb4` / `utf8mb4_unicode_ci` using your MySQL administration tool. Then run the migration and seeder below. The only domain table is `vacancies`; Laravel also maintains its `migrations` tracking table.

```powershell
php artisan config:clear
php artisan migrate
php artisan db:seed --class=VacancySeeder
php artisan about --only=environment,drivers
php artisan db:show --database=mysql
php artisan serve --no-reload --host=127.0.0.1 --port=8000
```

Open http://127.0.0.1:8000/ to be redirected to `/vacancies`. The listing shows all current vacancies, newest posting date first, with a descending identifier tie-breaker. `/up` remains the health check. Stop the server with Ctrl+C.

`npm run build` creates the production styles and scripts in `public/build`; the page works without an active Vite development server. Run it again after changing frontend assets. The lock file is tracked for repeatable npm installs; `node_modules` and generated builds stay ignored.

`--no-reload` is needed with the current local Herd PHP installation: ordinary `artisan serve` fails while launching its child process with "Unable to start curl module". Restart the server after changing `.env`.

## This computer's terminal

PHP and Composer are installed through Herd but are absent from the current terminal PATH. For the current PowerShell session:

```powershell
$env:PATH = "C:\Users\user\.config\herd\bin\php85;C:\Users\user\.config\herd\bin;" + $env:PATH
php --version
php C:\Users\user\.config\herd\bin\composer.phar --version
```

Use `php C:\Users\user\.config\herd\bin\composer.phar` in place of `composer` if no Composer launcher is available. Local verification used PHP 8.5.12; PHP 8.2 itself has not been run here.

## Checks

```powershell
composer validate --strict
composer check-platform-reqs
php artisan test
```

The tests use an isolated in-memory SQLite database and cover the root redirect, listing order and content, an empty listing, HTML escaping, position/company search, empty and blank searches, escaped query text, vacancy creation/editing/deletion, invalid/missing inputs, unchanged records after rejected updates, record isolation, missing-record responses, exactly six resource routes, retained form values, one-time success feedback, the vacancy schema, persistence and casts, protected model fields, 24 seed records, all three employment types, shared position/company search terms, and repeated seeding without duplicate fixtures. Listing tests disable Vite rendering so they can run before frontend assets are built; the styled page must also be checked through the development server. They do not modify the local MySQL database. Verify MySQL separately with `db:show` after configuring credentials.

`.env`, environment variants, Composer authentication, private key files, dependencies, logs, database files, caches, local build output and editor files are ignored. `.env.example` and `composer.lock` remain trackable. Never put real passwords or app keys in `.env.example`.

The future submission will need compiled frontend assets in its ZIP. `public/build` is currently ignored in Git per the Laravel scaffold; packaging compiled assets is a later milestone.

## UI specification

The local `docs/ui-specification.md` documents the visual direction, page structure, forms, responsive behavior, accessibility, and later acceptance checks. It is currently excluded from Git by the owner's ignore rule, so it will not be included in a clone or in this proposed commit. The listing, create page and edit page implement the shared layout and visual direction. Search is now implemented; employment filtering and pagination remain to be built.

The local MySQL connection was verified against server version 26.10.0 with the `vacancies_db` database. The planned MySQL 8 environment has not been tested.

## Vacancy data

The migration creates the required position, company, city, integer salary_from, employment_type, posted_at date, and Laravel identifier/timestamps. The Vacancy model allows mass assignment only for the six editable fields, casts salary to integer, and casts posted_at to a date.

VacancySeeder contains fictional demonstration jobs: 8 full-time, 8 part-time, and 8 remote. Search examples for later milestones include `Developer` (positions) and `BluePeak` (companies). Salaries have no assigned currency until that decision is confirmed. Seeding uses position/company to update existing fixtures or insert missing ones, so rerunning it does not duplicate the unchanged demo set. It resets matching fixture values and leaves unrelated vacancies intact. The default DatabaseSeeder calls VacancySeeder, so `php artisan db:seed` also works.

For a disposable database only, a clean rebuild is available:

```powershell
php artisan migrate:fresh
php artisan db:seed --class=VacancySeeder
```

`migrate:fresh` drops all existing tables. Use ordinary `migrate` for the existing local database. The data layer addresses the migration (3 points), model (1 point), and seeder (2 points) requirements in the supplied plan; final grading remains subject to the lecturer rubric.

## Vacancy listing

`VacancyController@index` loads vacancies with Eloquent. Exactly six named resource routes are implemented: `vacancies.index`, `vacancies.create`, `vacancies.store`, `vacancies.edit`, `vacancies.update`, and `vacancies.destroy`. There is no show route. The listing extends `layouts.app`, includes the shared header, and uses `@forelse` / `@empty` for results plus a visible `@if` for the role count or empty-list message. Desktop uses a semantic HTML table. At mobile/tablet widths, the table scrolls within a labeled, keyboard-focusable container. Employment types have readable badges; Blade escapes record values. Salaries show the stored numeric amount without an invented currency.

Manual review: visit `/`, confirm it redirects to `/vacancies`, inspect the data/order/badges, then narrow the browser and scroll the table horizontally. The automated tests exercise the empty state without clearing your MySQL records. Use Post vacancy in the header to create a record. Use Edit or Delete in a row to manage that vacancy. Use the GET search form to match a position or company. Employment filtering and pagination will appear in later milestones. The current implementation covers all six resource actions and all three required Blade pages; discovery and final submission checks remain.

## Vacancy creation

Open `/vacancies/create` using Post vacancy. The create page extends the common Blade layout and includes the reusable `vacancies._form` partial. All six fields are required. Position, company and city are strings up to 255 characters; salary is an integer between 0 and 2,147,483,647; employment type must be full-time, part-time or remote; posting date must use YYYY-MM-DD and fit the MySQL DATE range (1000-01-01 through 9999-12-31). Future posting dates are allowed. Currency remains unspecified.

The CSRF-protected form submits to `vacancies.store`. The controller validates first and passes only validated fields to Eloquent. A successful save redirects to the listing with a one-time success message. Invalid submissions return to the form with field-level errors, accessible error associations, and `old()` input values. Cancel and Back to vacancies return to the listing without saving. There is no authentication in this midterm scope.

Manual review: submit a valid vacancy and confirm it appears on the listing, then refresh to confirm the success message disappears. Submit incomplete or invalid fields to inspect validation; the browser also enforces basic input constraints. The automated tests additionally exercise server validation independently of browser checks. Local browser verification created one uniquely named temporary record, confirmed its MySQL values from a separate process, and removed only that record; the original 24 demo vacancies remain.

Creation supports the Create portion of CRUD, the create Blade page, CSRF, flash messages and validation requirements. The edit/delete step below completes the remaining CRUD actions.

## Editing and deleting vacancies

Edit opens a form prefilled with all six stored fields and reuses the creation form partial. It submits a CSRF-protected POST with `@method('PUT')`. Server validation uses the same rules as creation; an invalid update leaves the saved record unchanged and redisplays attempted `old()` values with field errors. A successful update redirects to the listing with one-time feedback. Route model binding returns 404 for a missing vacancy on edit, update or delete.

Delete opens an accessible in-page confirmation dialog, focused on Keep vacancy by default. Keep vacancy or Escape closes it without submitting. Delete vacancy submits the selected row's CSRF-protected form with `@method('DELETE')`. Delete buttons stay disabled until the confirmation script is ready, so deletion cannot proceed through the UI without confirmation when JavaScript is unavailable. Successful deletion redirects to the listing with one-time feedback. Deletion is permanent. There is no authentication by design for the midterm.

Manual review: edit one demonstration vacancy, change its fields, save and confirm the updated row. Try an invalid value to see validation, then cancel without saving. Click Delete and choose Keep vacancy to check cancellation. Only confirm deletion for a record you intend to remove. Local browser checks used disposable fixtures and verified the original 24 vacancies were unchanged afterward.

This step covers the edit page, edit/update/delete controller actions, the remaining named routes, method spoofing, confirmation, and mutation feedback. All three pages use `@extends` / `@section` with a shared layout and included partials. Full grading still depends on the remaining search, filter, pagination and submission requirements.

## Search and manual testing

The listing accepts a GET `search` parameter (nullable string, max 255 characters), trims it, and applies a grouped position LIKE OR company LIKE condition through Eloquent parameter binding. A nonempty term such as `0` is treated as a search. Results remain ordered by posting date and ID. Standard SQL LIKE wildcards `%` and `_` retain their pattern meaning. Search does not match city or employment type. Matching is case-insensitive under the configured MySQL collation.

The input and URL retain the term; Clear search restores the listing. A no-match search has a separate empty state and a View all roles action. Query text is escaped in the HTML. A grouped condition prepares search to combine correctly with employment filtering in the next step, but that filter is not implemented here. This step covers the supplied search criterion; pagination remains a separate milestone.

Follow [the complete manual test checklist](docs/manual-testing.md) to verify listing, create, validation, edit, delete/cancel, persistence, search and mobile/keyboard behavior using one disposable test vacancy. No database reset is required.
