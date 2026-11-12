# RoleBoard

KIU Laravel MVC midterm, Topic N8: Job Vacancy Board.

## Current milestone

Laravel 12 configured for MySQL database `vacancies_db`, with a Vacancy model, a vacancies migration, and 24 demonstration vacancies. Sessions and cache use files; queues run synchronously, so framework storage needs no database tables. The public vacancy listing now displays the seeded records through an Eloquent controller and shared Blade layout. Create/edit/delete forms, search, filtering and pagination are not implemented yet.

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

The tests use an isolated in-memory SQLite database and cover the root redirect, listing order and content, an empty listing, HTML escaping, the vacancy schema, persistence and casts, protected model fields, 24 seed records, all three employment types, shared position/company search terms, and repeated seeding without duplicate fixtures. Listing tests disable Vite rendering so they can run before frontend assets are built; the styled page must also be checked through the development server. They do not modify the local MySQL database. Verify MySQL separately with `db:show` after configuring credentials.

`.env`, environment variants, Composer authentication, private key files, dependencies, logs, database files, caches, local build output and editor files are ignored. `.env.example` and `composer.lock` remain trackable. Never put real passwords or app keys in `.env.example`.

The future submission will need compiled frontend assets in its ZIP. `public/build` is currently ignored in Git per the Laravel scaffold; packaging compiled assets is a later milestone.

## UI specification

The local `docs/ui-specification.md` documents the visual direction, page structure, forms, responsive behavior, accessibility, and later acceptance checks. It is currently excluded from Git by the owner's ignore rule, so it will not be included in a clone or in this proposed commit. The listing now implements the shared layout and visual direction; create/edit pages and later controls remain to be built.

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

`VacancyController@index` loads vacancies with Eloquent. The named `vacancies.index` route is the only resource route implemented at this checkpoint; the remaining five routes belong to CRUD milestones. The listing extends `layouts.app`, includes the shared header, and uses `@forelse` / `@empty` for results plus a visible `@if` for the role count or empty-list message. Desktop uses a semantic HTML table. At mobile/tablet widths, the table scrolls within a labeled, keyboard-focusable container. Employment types have readable badges; Blade escapes record values. Salaries show the stored numeric amount without an invented currency.

Manual review: visit `/`, confirm it redirects to `/vacancies`, inspect the data/order/badges, then narrow the browser and scroll the table horizontally. The automated tests exercise the empty state without clearing your MySQL records. Search/filter/pagination and mutation actions will appear only when implemented in their own steps. This checkpoint supports the Blade and listing/read portions of the rubric; it does not complete all six routes or the full Blade/CRUD criteria.
