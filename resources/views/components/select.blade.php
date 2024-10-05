@props([
    'disabled' => false,
    'options' => [],
    'selected' => null,
    'name' => '',
    'id' => '',
    'placeholder' => '',
    'multiple' => false,
])

@php
    $id = $id ?: $name;
@endphp

<select {{ $disabled ? 'disabled' : '' }} id="{{ $id }}" name="{{ $name }}" {!! $attributes->merge([
    'class' =>
        'w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 shadow-sm',
]) !!}
    {{ $multiple ? 'multiple' : '' }}>
    @if ($placeholder)
        <option value="">{{ $placeholder }}</option>
    @endif

    @foreach ($options as $key => $value)
        <option value="{{ $key }}" {{ $selected == $key ? 'selected' : '' }}>
            {{ $value }}
        </option>
    @endforeach
</select>
