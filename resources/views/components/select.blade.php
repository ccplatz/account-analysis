@props(['id', 'name' => $id, 'options', 'selected' => null])

<select id="{{ $id }}" name="{{ $name }}" {{ $attributes }}
    class="form-select @error($id) is-invalid @enderror">
    {{ $slot }}
    @foreach ($options as $option)
        <x-option :value="$option->id" :selected="old($id) ? old($id) == $option->id : $selected->id == $option->id">
            {{ __($option->description) }}
        </x-option>
    @endforeach
</select>
