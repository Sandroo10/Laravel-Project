@extends('layouts.app')

@section('title', 'Edit vacancy')

@section('content')
    <div class="form-page">
        <a class="back-link" href="{{ route('vacancies.index') }}"><span aria-hidden="true">←</span> Back to vacancies</a>

        <header class="form-heading">
            <p class="eyebrow"><span aria-hidden="true"></span> KEEP YOUR NEXT OPPORTUNITY UP TO DATE</p>
            <h1>A few details.<br><span>A better fit.</span></h1>
            <p>Update your vacancy so the right people have the right information.</p>
        </header>

        <section class="form-panel" aria-labelledby="form-heading">
            <div class="form-panel-heading">
                <div>
                    <h2 id="form-heading">Edit vacancy</h2>
                    <p>{{ $vacancy->position }} at {{ $vacancy->company }}</p>
                </div>
                <span class="required-note">All fields are required</span>
            </div>

            <form action="{{ route('vacancies.update', $vacancy) }}" method="POST">
                @csrf
                @method('PUT')
                @include('vacancies._form')

                <div class="form-actions">
                    <a class="button button-secondary" href="{{ route('vacancies.index') }}">Cancel</a>
                    <button class="button button-primary" type="submit">Save changes <span aria-hidden="true">↗</span></button>
                </div>
            </form>
        </section>
        <p class="form-footnote">Small updates can make a big difference.</p>
    </div>
@endsection
