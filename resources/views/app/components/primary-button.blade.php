<button {{ $attributes->merge(['type' => 'submit', 'class' => 'app-btn-primary']) }}>
    {{ $slot }}
</button>
