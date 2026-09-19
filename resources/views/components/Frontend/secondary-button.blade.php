<button {{ $attributes->merge(['type' => 'button', 'class' => 'btn btn-light border']) }}>
    {{ $slot }}
</button>