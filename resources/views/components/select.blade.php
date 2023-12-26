@props(['id', 'name' => $id, 'options', 'selected' => null])

<select id="{{ $id }}" name="{{ $name }}" {{ $attributes }}
    class="form-select @error($id) is-invalid @enderror">
    {{ $slot }}
    @foreach ($options as $option)
        <x-option :value="$option->id" :selected="$selected ? $selected->id == $option->id : false">
            {{ __($option->description) }}
        </x-option>
    @endforeach
</select>
