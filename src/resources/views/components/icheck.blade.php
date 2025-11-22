@props(['name', 'value', 'checked' => null, 'type' => 'primary', 'divClass' => '', 'labelClass' => ''])
@php
  $id = 'icheck-' . sha1($type . '/' . $divClass . '/' . $labelClass . '/' . $name . '/' . $value);
@endphp

<div class="icheck-{{ $type }} {{ $divClass }}">
  <x-input
    type="checkbox"
    :name="$name"
    :value="$value"
    :checked="$checked"
    :id="$id"
    {{ $attributes }}
  />
  <label for="{{ $id }}" class="font-weight-normal {{ $labelClass }}">{{ $slot }}</label>
</div>
