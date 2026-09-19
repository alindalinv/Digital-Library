@props([
    'name',
    'show' => false,
    'maxWidth' => '2xl',
])

@php
    $modalSize = [
        'sm' => 'modal-sm',
        'md' => '',
        'lg' => 'modal-lg',
        'xl' => 'modal-xl',
        '2xl' => 'modal-xl',
    ][$maxWidth] ?? '';
@endphp

<div class="modal fade" id="{{ $name }}" tabindex="-1" aria-labelledby="{{ $name }}-label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered {{ $modalSize }}">
        <div class="modal-content">
            {{ $slot }}
        </div>
    </div>
</div>