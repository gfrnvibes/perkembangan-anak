@props(['active' => false])

@php
    $classes = $active ?? false ? 'nav-link active fw-bold' : 'nav-link text-light';
@endphp

<li class="nav-item">
    <a wire:navigate {{ $attributes->merge(['class' => $classes]) }}> {{ $slot }} </a>
</li>