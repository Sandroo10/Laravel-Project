# RoleBoard

KIU Laravel MVC midterm, Topic N8: Job Vacancy Board.

## Current milestone

Laravel 12 configured for MySQL database `vacancies_db`, with a Vacancy model, a vacancies migration, and 24 demonstration vacancies. Sessions and cache use files; queues run synchronously, so framework storage needs no database tables. The public vacancy listing now displays the seeded records through an Eloquent controller and shared Blade layout. Create, edit and delete workflows are implemented with validation, confirmation and success feedback. Position/company search and employment-type filtering work together. Results are paginated at 10 vacancies per page, preserving the active query parameters.

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
```

Verification uses manual browser checks, database inspection and asset builds. No automated test suite is maintained.

`.env`, environment variants, Composer authentication, private key files, dependencies, logs, database files, caches, local build output and editor files are ignored. `.env.example` and `composer.lock` remain trackable. Never put real passwords or app keys in `.env.example`.

The future submission will need compiled frontend assets in its ZIP. `public/build` is currently ignored in Git per the Laravel scaffold; packaging compiled assets is a later milestone.

## UI specification

The local `docs/ui-specification.md` documents the visual direction, page structure, forms, responsive behavior, accessibility, and later acceptance checks. It is currently excluded from Git by the owner's ignore rule, so it will not be included in a clone or in this proposed commit. The listing, create page and edit page implement the shared layout and visual direction. Search, employment filtering and pagination are implemented.

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

Manual review: visit `/`, confirm it redirects to `/vacancies`, inspect the data/order/badges, then narrow the browser and scroll the table horizontally. Use Post vacancy in the header to create a record. Use Edit or Delete in a row to manage that vacancy. Use the GET search form to match a position or company. Select an employment type to combine it with search. Use the numbered page links or Previous/Next to browse 10 roles per page. The current implementation covers all six resource actions and all three required Blade pages; discovery and final submission checks remain.

## Vacancy creation

Open `/vacancies/create` using Post vacancy. The create page extends the common Blade layout and includes the reusable `vacancies._form` partial. All six fields are required. Position, company and city are strings up to 255 characters; salary is an integer between 0 and 2,147,483,647; employment type must be full-time, part-time or remote; posting date must use YYYY-MM-DD and fit the MySQL DATE range (1000-01-01 through 9999-12-31). Future posting dates are allowed. Currency remains unspecified.

The CSRF-protected form submits to `vacancies.store`. The controller validates first and passes only validated fields to Eloquent. A successful save redirects to the listing with a one-time success message. Invalid submissions return to the form with field-level errors, accessible error associations, and `old()` input values. Cancel and Back to vacancies return to the listing without saving. There is no authentication in this midterm scope.

Manual review: submit a valid vacancy and confirm it appears on the listing, then refresh to confirm the success message disappears. Submit incomplete or invalid fields to inspect validation; the browser also enforces basic input constraints. Local browser verification created one uniquely named temporary record, confirmed its MySQL values from a separate process, and removed only that record; the original 24 demo vacancies remain.

Creation supports the Create portion of CRUD, the create Blade page, CSRF, flash messages and validation requirements. The edit/delete step below completes the remaining CRUD actions.

## Editing and deleting vacancies

Edit opens a form prefilled with all six stored fields and reuses the creation form partial. It submits a CSRF-protected POST with `@method('PUT')`. Server validation uses the same rules as creation; an invalid update leaves the saved record unchanged and redisplays attempted `old()` values with field errors. A successful update redirects to the listing with one-time feedback. Route model binding returns 404 for a missing vacancy on edit, update or delete.

Delete opens an accessible in-page confirmation dialog, focused on Keep vacancy by default. Keep vacancy or Escape closes it without submitting. Delete vacancy submits the selected row's CSRF-protected form with `@method('DELETE')`. Delete buttons stay disabled until the confirmation script is ready, so deletion cannot proceed through the UI without confirmation when JavaScript is unavailable. Successful deletion redirects to the listing with one-time feedback. Deletion is permanent. There is no authentication by design for the midterm.

Manual review: edit one demonstration vacancy, change its fields, save and confirm the updated row. Try an invalid value to see validation, then cancel without saving. Click Delete and choose Keep vacancy to check cancellation. Only confirm deletion for a record you intend to remove. Local browser checks used disposable fixtures and verified the original 24 vacancies were unchanged afterward.

This step covers the edit page, edit/update/delete controller actions, the remaining named routes, method spoofing, confirmation, and mutation feedback. All three pages use `@extends` / `@section` with a shared layout and included partials. Full grading still depends on the final polish and submission requirements.

## Search and manual testing

The listing accepts a GET `search` parameter (nullable string, max 255 characters), trims it, and applies a grouped position LIKE OR company LIKE condition through Eloquent parameter binding. A nonempty term such as `0` is treated as a search. Results remain ordered by posting date and ID. Standard SQL LIKE wildcards `%` and `_` retain their pattern meaning. Search does not match city or employment type. Matching is case-insensitive under the configured MySQL collation.

The input and URL retain the term; Clear search restores the listing. A no-match search has a separate empty state and a View all roles action. Query text is escaped in the HTML. The grouped search condition now combines with an exact employment-type filter, so both position and company matches must satisfy the selected type. This covers the supplied search criterion and works with the pagination described below.

The local `docs/manual-testing.md` checklist covers listing, create, validation, edit, delete/cancel, persistence, search, combined filtering and mobile/keyboard behavior using one disposable test vacancy. It is excluded from Git by the owner's ignore rule. No database reset is required.

## Employment filtering

The same GET form includes `employment_type`: blank means All employment types; otherwise it must be `full-time`, `part-time`, or `remote`. Invalid values or arrays are rejected by validation. The controller applies a bound Eloquent equality condition alongside the grouped position/company LIKE query. Changing the employment dropdown automatically submits the GET form, preserving the current search text. Search roles remains available for submitting typed searches and as a fallback when JavaScript is disabled. Search text and selected type remain in the form and URL after submission. Clear filters resets both values; choosing All employment types and submitting retains any search text. Active filters show a matching-role count and summary, with a helpful empty state when no combination matches.

On the unchanged 24-record demo set, each type has 8 vacancies. Developer plus Remote returns 3, Developer plus Full-time returns 2, and Developer plus Part-time returns 1. BluePeak plus Remote returns only Technical Writer, while BluePeak plus Part-time returns no results. See section 3b of the manual checklist for the full verification matrix. This covers the employment-filter criterion and its interaction with search.

## Pagination

The controller calls `paginate(10)->withQueryString()` after the grouped search and employment filter, keeping the existing posting-date/ID order. The role-count badge uses the total across all pages, not the current page count. The shared pagination partial renders numbered links, Previous/Next, a current-page indicator and the visible record range. Search and employment type remain in navigation URLs. Submitting the search form or changing employment type starts at page 1 because the form does not carry the old page parameter.

The original 24 records produce 10 roles on page 1, 10 on page 2 and 4 on page 3. Results with only one page do not show pagination controls. An out-of-range page displays a recovery link to page 1 while retaining search/filter choices. Empty searches retain their separate no-match state.

Manual browser verification covered 10/10/4 page sizes, previous/next navigation, mobile controls, total counts, out-of-range recovery, and resetting the page on filter changes. Three disposable records were temporarily created through the normal form so an 11-record result set could demonstrate both a nonempty search and Remote filter surviving page-2 navigation; all three were removed afterward. The original 24 records remain. No automated tests were added or run. This step covers the pagination criterion and query-parameter preservation.

## Validation and accessibility refinements

Create and edit errors now include a focusable summary with links to the affected fields. On a server validation failure, the small frontend script focuses that summary; following an error link moves focus to its input. Existing field-level errors, `aria-invalid` associations and `old()` values remain. The shared form normalizes old values to scalar strings before rendering, so malformed array input is not printed into HTML controls. Valid model defaults, including the edit date, are preserved.

Delete confirmation identifies the selected position and company using escaped markup and JavaScript `textContent`. The dialog has a viewport-based maximum height and can scroll when necessary. Narrow phones stack its action buttons. Keep vacancy remains the initial focus, and Escape cancels without submitting. No database schema or validation rules changed in this refinement.

Manual checks covered invalid create/edit submissions, retained values, summary focus and error-link focus, 320/375/768/1440px form widths, a 320×480px dialog, keyboard cancellation and unchanged listing data. The production asset build and Blade compilation passed. No automated tests were added or run; no review records were created, and the original 24 vacancies remain.
## CSS organization

`resources/css/app.css` is the Vite/Tailwind entry point. It imports the shared stylesheet and each component stylesheet, then declares the Blade/JavaScript source paths. Vite still produces one application CSS asset; the existing Blade `@vite` entry does not change.

- `resources/css/shared.css`: theme tokens, base styles, page container, skip link, common headings/eyebrows, reusable button variants, field hints/errors, focus behavior and reduced-motion support.
- `resources/css/components/header.css` and `footer.css`: shared site navigation, branding and footer components.
- `hero.css`, `vacancy-board.css`, `vacancy-table.css`, `vacancy-actions.css`, `employment-badge.css` and `empty-state.css`: the listing's individual visual components.
- `vacancy-form.css` and `validation-summary.css`: the form reused by create/edit and its validation feedback.
- `search-filter.css`, `pagination.css`, `confirmation-dialog.css` and `flash-message.css`: search/filter controls, page navigation, delete confirmation and mutation feedback.

Each component keeps its own responsive rules in the same file. Put a style in shared.css when it is a general primitive used across different components; keep a reusable component's internal styles in that component's file. Add a single import to app.css when introducing another component stylesheet. Run `npm run build` after changes.

This refactor retains the existing selectors, declarations and responsive behavior. The production build and manual desktop/mobile/tablet checks passed, including listing, forms and delete-dialog cancellation. No automated tests were added or run.
