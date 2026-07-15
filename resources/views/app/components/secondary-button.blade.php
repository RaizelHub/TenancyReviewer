<button {{ $attributes->merge(['type' => 'button', 'class' => 'app-btn-secondary']) }}>
    {{ $slot }}
</button>
