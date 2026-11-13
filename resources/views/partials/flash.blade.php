@if(session('success'))
    <div class="flash-message" role="status">
        <span class="flash-icon" aria-hidden="true">✓</span>
        <p>{{ session('success') }}</p>
    </div>
@endif
