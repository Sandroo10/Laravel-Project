# RoleBoard

KIU Laravel MVC midterm, Topic N8: Job Vacancy Board.

## Current milestone

Laravel 12 configured for MySQL database `vacancies_db`, with a Vacancy model, a vacancies migration, and 24 demonstration vacancies. Sessions and cache use files; queues run synchronously, so framework storage needs no database tables. The stock Laravel welcome route/view remains only as a startup check. Controllers, vacancy pages, CRUD, search, filtering and pagination are not implemented yet.

Project instructions supplied by the owner are preserved in `AGENTS.md`. The two supplied planning documents informed this setup; the original lecturer rubric and lecture files were not present in the workspace.

## Requirements

- PHP 8.2+ with Composer 2 and Laravel's required extensions, including `pdo_mysql`.
- MySQL 8 and a local database account with access to `vacancies_db`.
- Node.js/npm are needed for frontend assets in later milestones.

Composer resolves dependencies against PHP 8.2.0 for compatibility. The lock file is included for repeatable installations.

## Local setup

```powershell
composer install
Copy-Item .env.example .env
php artisan key:generate
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

Open http://127.0.0.1:8000/ for the unchanged Laravel welcome screen, or `/up` for the health check. Stop the server with Ctrl+C.

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

The tests use an isolated in-memory SQLite database and cover startup, the vacancy schema, persistence and casts, protected model fields, 24 seed records, all three employment types, shared position/company search terms, and repeated seeding without duplicate fixtures. They do not modify the local MySQL database. Verify MySQL separately with `db:show` after configuring credentials.

`.env`, environment variants, Composer authentication, private key files, dependencies, logs, database files, caches, local build output and editor files are ignored. `.env.example` and `composer.lock` remain trackable. Never put real passwords or app keys in `.env.example`.

The future submission will need compiled frontend assets in its ZIP. `public/build` is currently ignored in Git per the Laravel scaffold; packaging compiled assets is a later milestone.

## UI specification

The local `docs/ui-specification.md` documents the visual direction, page structure, forms, responsive behavior, accessibility, and later acceptance checks. It is currently excluded from Git by the owner's ignore rule, so it will not be included in a clone or in this proposed commit. The data layer is now prepared; application pages remain at the Laravel welcome screen.

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
