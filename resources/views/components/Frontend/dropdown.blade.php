@props([
    'align' => 'right',
    'width' => '48',
    'contentClasses' => 'py-1 bg-white',
])

@php
$alignmentClasses = match ($align) {
    'left' => 'dropdown-menu-start',
    'top'  => '',
    default => 'dropdown-menu-end',
};
@endphp

<div class="dropdown">
    <div data-bs-toggle="dropdown" aria-expanded="false">
        {{ $trigger }}
    </div>

    <div class="dropdown-menu {{ $alignmentClasses }} {{ $contentClasses }}">
        {{ $content }}
    </div>
</div>