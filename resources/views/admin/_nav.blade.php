@php
    $isSuggestions = request()->routeIs('admin.suggestions.*');
@endphp

<ul class="nav nav-tabs mb-4">
    <li class="nav-item">
        <a class="nav-link {{ $isSuggestions ? '' : 'active' }}" href="{{ route('admin.software.index') }}">
            Perangkat Lunak
        </a>
    </li>
    @if (Route::has('admin.suggestions.index'))
        <li class="nav-item">
            <a class="nav-link {{ $isSuggestions ? 'active' : '' }}" href="{{ route('admin.suggestions.index') }}">
                Usulan Masuk
            </a>
        </li>
    @endif
</ul>
