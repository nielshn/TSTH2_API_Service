@props(['route', 'label'])

@php
    $isActive = request()->routeIs($route . '*');
@endphp

<li class="nav-item">
    <a href="{{ route($route . '.index') }}" class="nav-link {{ $isActive ? 'active' : '' }}">
        {{ $label }}
    </a>
</li>
