@extends('layouts.app')

@section('title', 'Post a vacancy')

@section('content')
    <div class="form-page">
        <a class="back-link" href="{{ route('vacancies.index') }}"><span aria-hidden="true">←</span> Back to vacancies</a>

        <header class="form-heading">
            <p class="eyebrow"><span aria-hidden="true"></span> GREAT TEAMS START WITH A GREAT ROLE</p>
            <h1>Make room for <span>great talent.</span></h1>
            <p>Share the details. Help someone find their next chapter.</p>
        </header>

        <section class="form-panel" aria-labelledby="form-heading">
            <div class="form-panel-heading">
                <div>
                    <h2 id="form-heading">Post a vacancy</h2>
                    <p>A clear description starts with the essentials.</p>
                </div>
                <span class="required-note">All fields are required</span>
            </div>

            <form action="{{ route('vacancies.store') }}" method="POST">
                @csrf
                @include('vacancies._form')

                <div class="form-actions">
                    <a class="button button-secondary" href="{{ route('vacancies.index') }}">Cancel</a>
                    <button class="button button-primary" type="submit">Post vacancy <span aria-hidden="true">↗</span></button>
                </div>
            </form>
        </section>
        <p class="form-footnote">The next right person could be one opportunity away.</p>
    </div>
@endsection
