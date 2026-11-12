# RoleBoard

KIU Laravel MVC midterm, Topic N8: Job Vacancy Board.

## Milestone 1

Laravel 12 foundation configured for MySQL database `vacancies_db`. Sessions and cache use files; queues run synchronously, so framework storage needs no database tables. The stock Laravel welcome route/view remains only as a startup check. No vacancy functionality, authentication, migrations, factories, or seeders are implemented.

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

Copy the environment file only on a fresh installation; do not overwrite an existing `.env`. Set `DB_USERNAME` and `DB_PASSWORD` in `.env` to your local MySQL account. The example username `roleboard` is a placeholder, not a provisioned account. Create an empty `vacancies_db` database with `utf8mb4` / `utf8mb4_unicode_ci` using your MySQL administration tool. No tables are required or created at this milestone.

```powershell
php artisan config:clear
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

The starter tests verify a basic assertion and a successful request to `/`; they do not verify MySQL. Verify MySQL separately with `db:show` after configuring credentials.

`.env`, environment variants, Composer authentication, private key files, dependencies, logs, database files, caches, local build output and editor files are ignored. `.env.example` and `composer.lock` remain trackable. Never put real passwords or app keys in `.env.example`.

The future submission will need compiled frontend assets in its ZIP. `public/build` is currently ignored in Git per the Laravel scaffold; packaging compiled assets is a later milestone.

No commits or pushes have been made.
