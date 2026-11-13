<header class="site-header">
    <div class="page-container header-content">
        <a class="brand" href="{{ route('vacancies.index') }}" aria-label="RoleBoard home">
            <span class="brand-mark" aria-hidden="true">r<span>.</span></span>
            <span>RoleBoard<span class="brand-period" aria-hidden="true">.</span></span>
        </a>
        <nav class="header-nav" aria-label="Main navigation">
            <a class="nav-link nav-find-role" href="{{ route('vacancies.index') }}" @if(request()->routeIs('vacancies.index')) aria-current="page" @endif>
                Find a role <span aria-hidden="true">↗</span>
            </a>
            <a class="button button-primary" href="{{ route('vacancies.create') }}" @if(request()->routeIs('vacancies.create')) aria-current="page" @endif>Post vacancy <span aria-hidden="true">+</span></a>
        </nav>
    </div>
</header>
