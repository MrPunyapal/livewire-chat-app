@props([
    'disabled' => false,
    'options' => [],
    'selected' => [],
    'name' => '',
    'id' => '',
    'placeholder' => 'Select options',
    'multiple' => true,
    'wireModel' => null,
])

@php
    $id = $id ?: $name;
    $wireModelName = $wireModel ?: $name;
    $formattedOptions = collect($options)->map(fn ($label, $value) => [
        'value' => (string) $value,
        'label' => $label,
    ])->values()->toArray();
@endphp

<div
    class="w-full"
    x-data="multiSelect({
        multiple: @js($multiple),
        value: @js($selected),
        options: @js($formattedOptions),
        wireModel: @js($name),
        placeholder: @js($placeholder),
    })"
    wire:ignore
>
    <select x-ref="select" :multiple="multiple" {{ $disabled ? 'disabled' : '' }}></select>
</div>
