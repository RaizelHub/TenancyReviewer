@props(['value'])

<label {{ $attributes->merge(['class' => 'app-label']) }}>
    {{ $value ?? $slot }}
</label>
