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
            @if($vacancies->isNotEmpty())
                <span class="vacancy-count"><span aria-hidden="true"></span>{{ $vacancies->count() }} {{ $vacancies->count() === 1 ? 'open role' : 'open roles' }}</span>
            @else
                <span class="panel-note">Something good is on the horizon.</span>
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
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="empty-state">
                                <span class="empty-icon" aria-hidden="true">↗</span>
                                <h3>Your next opportunity is on its way.</h3>
                                <p>No vacancies have been posted yet. Check back soon for new roles.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="panel-footer"><span aria-hidden="true">✦</span> Different paths. New possibilities.</div>
    </section>
@endsection
