@props(['value' => null, 'selected'])

<option value="{{ $value }}" @selected($selected) {{ $attributes }}>
    {{ $slot }}
</option>
