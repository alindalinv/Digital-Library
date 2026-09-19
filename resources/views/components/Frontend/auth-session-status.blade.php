@props(['status'])

@if ($status)
    <div {{ $attributes->merge(['class' => 'alert alert-success small mb-0']) }}>
        {{ $status }}
    </div>
@endif