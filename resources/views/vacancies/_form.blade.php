@php
    $fieldValues = [
        'position' => $vacancy->position ?? '',
        'company' => $vacancy->company ?? '',
        'city' => $vacancy->city ?? '',
        'salary_from' => $vacancy->salary_from ?? '',
        'employment_type' => $vacancy->employment_type ?? '',
        'posted_at' => isset($vacancy) ? $vacancy->posted_at->format('Y-m-d') : '',
    ];

    foreach ($fieldValues as $field => $default) {
        $oldValue = old($field, $default);
        $fieldValues[$field] = is_scalar($oldValue) ? (string) $oldValue : '';
    }
@endphp

<div class="form-fields">
    @if($errors->any())
        <div class="error-summary" role="alert" tabindex="-1" data-validation-summary aria-labelledby="validation-heading">
            <h3 id="validation-heading">Please check the highlighted fields.</h3>
            <p>Your vacancy has not been saved. Your entries are still below.</p>
            <ul>
                @foreach($errors->getMessages() as $field => $messages)
                    <li><a href="#{{ $field }}">{{ $messages[0] }}</a></li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="form-grid">
        <div class="form-field">
            <label for="position">Position</label>
            <input id="position" name="position" type="text" value="{{ $fieldValues['position'] }}" maxlength="255" placeholder="e.g. Laravel Developer" required @error('position') aria-invalid="true" aria-describedby="position-error" @enderror>
            @error('position')<p id="position-error" class="field-error">{{ $message }}</p>@enderror
        </div>

        <div class="form-field">
            <label for="company">Company</label>
            <input id="company" name="company" type="text" value="{{ $fieldValues['company'] }}" maxlength="255" placeholder="e.g. BluePeak Software" required @error('company') aria-invalid="true" aria-describedby="company-error" @enderror>
            @error('company')<p id="company-error" class="field-error">{{ $message }}</p>@enderror
        </div>

        <div class="form-field">
            <label for="city">City</label>
            <input id="city" name="city" type="text" value="{{ $fieldValues['city'] }}" maxlength="255" placeholder="e.g. Kutaisi" required @error('city') aria-invalid="true" aria-describedby="city-error" @enderror>
            @error('city')<p id="city-error" class="field-error">{{ $message }}</p>@enderror
        </div>

        <div class="form-field">
            <label for="salary_from">Salary from</label>
            <input id="salary_from" name="salary_from" type="number" value="{{ $fieldValues['salary_from'] }}" min="0" max="2147483647" step="1" placeholder="e.g. 2500" required aria-describedby="salary-hint{{ $errors->has('salary_from') ? ' salary_from-error' : '' }}" @error('salary_from') aria-invalid="true" @enderror>
            <p id="salary-hint" class="field-hint">Starting amount, as a whole number.</p>
            @error('salary_from')<p id="salary_from-error" class="field-error">{{ $message }}</p>@enderror
        </div>

        <div class="form-field">
            <label for="employment_type">Employment type</label>
            <select id="employment_type" name="employment_type" required @error('employment_type') aria-invalid="true" aria-describedby="employment_type-error" @enderror>
                <option value="">Choose an employment type</option>
                @foreach(['full-time' => 'Full-time', 'part-time' => 'Part-time', 'remote' => 'Remote'] as $value => $label)
                    <option value="{{ $value }}" @selected($fieldValues['employment_type'] === $value)>{{ $label }}</option>
                @endforeach
            </select>
            @error('employment_type')<p id="employment_type-error" class="field-error">{{ $message }}</p>@enderror
        </div>

        <div class="form-field">
            <label for="posted_at">Posted date</label>
            <input id="posted_at" name="posted_at" type="date" value="{{ $fieldValues['posted_at'] }}" min="1000-01-01" max="9999-12-31" required @error('posted_at') aria-invalid="true" aria-describedby="posted_at-error" @enderror>
            @error('posted_at')<p id="posted_at-error" class="field-error">{{ $message }}</p>@enderror
        </div>
    </div>
</div>
