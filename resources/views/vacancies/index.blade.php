@extends('layouts.app')

@section('title', 'Find your next role')

@section('content')
    <section class="listing-hero" aria-labelledby="page-heading">
        <div>
            <p class="eyebrow"><span aria-hidden="true"></span> A little ambition. A world of possibility.</p>
            <h1 id="page-heading">Good work.<br><span>Great possibilities.</span></h1>
            <p class="hero-description">Your skills deserve the right opportunity. Discover a role<br class="desktop-break"> that feels like the next step, not just another job.</p>
        </div>
        <div class="hero-note">
            <span class="note-icon" aria-hidden="true">↗</span>
            <p>A fresh perspective.<br>A new beginning.<br><strong>Your next chapter.</strong></p>
            <span class="note-line" aria-hidden="true"></span>
            <span class="note-caption">MAKE YOUR NEXT MOVE</span>
        </div>
    </section>

    <section class="vacancy-panel" aria-labelledby="vacancies-heading">
        <div class="panel-heading">
            <div>
                <p class="eyebrow section-eyebrow">THE OPPORTUNITY BOARD</p>
                <h2 id="vacancies-heading">Find your next role</h2>
            </div>
            @if($search !== '' || $employmentType !== '')
                <span class="vacancy-count"><span aria-hidden="true"></span>{{ $vacancies->count() }} {{ $vacancies->count() === 1 ? 'matching role' : 'matching roles' }}</span>
            @elseif($vacancies->isNotEmpty())
                <span class="vacancy-count"><span aria-hidden="true"></span>{{ $vacancies->count() }} {{ $vacancies->count() === 1 ? 'open role' : 'open roles' }}</span>
            @else
                <span class="panel-note">Something good is on the horizon.</span>
            @endif
        </div>

        <div class="search-section">
            <form class="search-form" action="{{ route('vacancies.index') }}" method="GET" role="search">
                <div class="search-field">
                    <label for="search">Find a position or company</label>
                    <input id="search" name="search" type="search" value="{{ $search }}" maxlength="255" placeholder="Try Developer or BluePeak" @error('search') aria-invalid="true" aria-describedby="search-error" @enderror>
                    @error('search')<p id="search-error" class="field-error">{{ $message }}</p>@enderror
                </div>
                <div class="search-field employment-filter">
                    <label for="employment_type">Employment type</label>
                    <select id="employment_type" name="employment_type" data-auto-submit aria-describedby="employment-filter-hint{{ $errors->has('employment_type') ? ' employment-filter-error' : '' }}" @error('employment_type') aria-invalid="true" @enderror>
                        <option value="">All employment types</option>
                        @foreach(['full-time' => 'Full-time', 'part-time' => 'Part-time', 'remote' => 'Remote'] as $value => $label)
                            <option value="{{ $value }}" @selected($employmentType === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                    <span id="employment-filter-hint" class="sr-only">Changing employment type automatically updates the results.</span>
                    @error('employment_type')<p id="employment-filter-error" class="field-error">{{ $message }}</p>@enderror
                </div>
                <button type="submit" class="button button-primary">Search roles <span aria-hidden="true">↗</span></button>
                @if($search !== '' || $employmentType !== '')
                    <a class="search-clear" href="{{ route('vacancies.index') }}">{{ $employmentType !== '' ? 'Clear filters' : 'Clear search' }}</a>
                @endif
            </form>
            @if($search !== '')
                <p class="search-summary" role="status">Results for <strong>“{{ $search }}”</strong>@if($employmentType !== '') · <strong>{{ ucfirst($employmentType) }}</strong>@endif</p>
            @elseif($employmentType !== '')
                <p class="search-summary" role="status">Showing <strong>{{ ucfirst($employmentType) }}</strong> roles</p>
            @endif
        </div>

        <p id="table-hint" class="table-hint">Swipe or scroll across to see all vacancy details.</p>
        <div class="table-scroll" role="region" aria-label="Vacancy listings" aria-describedby="table-hint" tabindex="0">
            <table class="vacancy-table">
                <caption class="sr-only">Available vacancies, most recently posted first</caption>
                <thead>
                    <tr>
                        <th scope="col">Position</th>
                        <th scope="col">Company</th>
                        <th scope="col">Location</th>
                        <th scope="col">Salary from</th>
                        <th scope="col">Employment</th>
                        <th scope="col">Posted</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($vacancies as $vacancy)
                        <tr>
                            <th scope="row">{{ $vacancy->position }}</th>
                            <td class="company-cell">{{ $vacancy->company }}</td>
                            <td>{{ $vacancy->city }}</td>
                            <td class="salary-cell">{{ number_format($vacancy->salary_from) }}</td>
                            <td>
                                <span @class([
                                    'employment-badge',
                                    'badge-full-time' => $vacancy->employment_type === 'full-time',
                                    'badge-part-time' => $vacancy->employment_type === 'part-time',
                                    'badge-remote' => $vacancy->employment_type === 'remote',
                                ])>
                                    <span aria-hidden="true"></span>{{ ucfirst($vacancy->employment_type) }}
                                </span>
                            </td>
                            <td class="date-cell"><time datetime="{{ $vacancy->posted_at->format('Y-m-d') }}">{{ $vacancy->posted_at->format('M j, Y') }}</time></td>
                            <td>
                                <div class="row-actions">
                                    <a class="row-action edit-action" href="{{ route('vacancies.edit', $vacancy) }}" aria-label="Edit {{ $vacancy->position }} at {{ $vacancy->company }}">Edit</a>
                                    <form action="{{ route('vacancies.destroy', $vacancy) }}" method="POST" data-confirm-delete>
                                        @csrf
                                        @method('DELETE')
                                        <button class="row-action delete-action" type="submit" aria-label="Delete {{ $vacancy->position }} at {{ $vacancy->company }}" disabled>Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="empty-state">
                                <span class="empty-icon" aria-hidden="true">↗</span>
                                @if($search !== '' || $employmentType !== '')
                                    <h3>No matching roles just yet.</h3>
                                    <p>Try another search or employment type, or explore all available roles.</p>
                                    <a class="button button-primary empty-action" href="{{ route('vacancies.index') }}">View all roles</a>
                                @else
                                    <h3>Your next opportunity is on its way.</h3>
                                    <p>No vacancies have been posted yet. Check back soon for new roles.</p>
                                    <a class="button button-primary empty-action" href="{{ route('vacancies.create') }}">Post the first vacancy</a>
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="panel-footer"><span aria-hidden="true">✦</span> Different paths. New possibilities.</div>
    </section>

    <noscript><p class="field-hint">Enable JavaScript to use the delete confirmation.</p></noscript>
    <dialog id="delete-confirmation" class="confirmation-dialog" aria-labelledby="delete-heading" aria-describedby="delete-description">
        <h2 id="delete-heading">Delete this vacancy?</h2>
        <p id="delete-description">The vacancy will be removed from the board. This cannot be undone.</p>
        <form method="dialog" class="confirmation-actions">
            @csrf
            <button class="button button-secondary" value="cancel" autofocus>Keep vacancy</button>
            <button class="button button-danger" value="delete">Delete vacancy</button>
        </form>
    </dialog>
@endsection
