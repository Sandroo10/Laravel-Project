<div class="form-fields">
    @if($errors->any())
        <div class="error-summary" role="alert">
            <h3>Please check the highlighted fields.</h3>
            <p>Your vacancy has not been saved. Your entries are still below.</p>
        </div>
    @endif

    <div class="form-grid">
        <div class="form-field">
            <label for="position">Position</label>
            <input id="position" name="position" type="text" value="{{ old('position', $vacancy->position ?? '') }}" maxlength="255" placeholder="e.g. Laravel Developer" required @error('position') aria-invalid="true" aria-describedby="position-error" @enderror>
            @error('position')<p id="position-error" class="field-error">{{ $message }}</p>@enderror
        </div>

        <div class="form-field">
            <label for="company">Company</label>
            <input id="company" name="company" type="text" value="{{ old('company', $vacancy->company ?? '') }}" maxlength="255" placeholder="e.g. BluePeak Software" required @error('company') aria-invalid="true" aria-describedby="company-error" @enderror>
            @error('company')<p id="company-error" class="field-error">{{ $message }}</p>@enderror
        </div>

        <div class="form-field">
            <label for="city">City</label>
            <input id="city" name="city" type="text" value="{{ old('city', $vacancy->city ?? '') }}" maxlength="255" placeholder="e.g. Kutaisi" required @error('city') aria-invalid="true" aria-describedby="city-error" @enderror>
            @error('city')<p id="city-error" class="field-error">{{ $message }}</p>@enderror
        </div>

        <div class="form-field">
            <label for="salary_from">Salary from</label>
            <input id="salary_from" name="salary_from" type="number" value="{{ old('salary_from', $vacancy->salary_from ?? '') }}" min="0" max="2147483647" step="1" placeholder="e.g. 2500" required aria-describedby="salary-hint{{ $errors->has('salary_from') ? ' salary_from-error' : '' }}" @error('salary_from') aria-invalid="true" @enderror>
            <p id="salary-hint" class="field-hint">Starting amount, as a whole number.</p>
            @error('salary_from')<p id="salary_from-error" class="field-error">{{ $message }}</p>@enderror
        </div>

        <div class="form-field">
            <label for="employment_type">Employment type</label>
            <select id="employment_type" name="employment_type" required @error('employment_type') aria-invalid="true" aria-describedby="employment_type-error" @enderror>
                <option value="">Choose an employment type</option>
                @foreach(['full-time' => 'Full-time', 'part-time' => 'Part-time', 'remote' => 'Remote'] as $value => $label)
                    <option value="{{ $value }}" @selected(old('employment_type', $vacancy->employment_type ?? '') === $value)>{{ $label }}</option>
                @endforeach
            </select>
            @error('employment_type')<p id="employment_type-error" class="field-error">{{ $message }}</p>@enderror
        </div>

        <div class="form-field">
            <label for="posted_at">Posted date</label>
            <input id="posted_at" name="posted_at" type="date" value="{{ old('posted_at', isset($vacancy) ? $vacancy->posted_at->format('Y-m-d') : '') }}" min="1000-01-01" max="9999-12-31" required @error('posted_at') aria-invalid="true" aria-describedby="posted_at-error" @enderror>
            @error('posted_at')<p id="posted_at-error" class="field-error">{{ $message }}</p>@enderror
        </div>
    </div>
</div>
