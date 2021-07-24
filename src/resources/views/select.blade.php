@php
  $calculates = $calculate($errors, old());
@endphp

<select {!! $attributes->class([config('form.error') => $calculates['hasError']])->merge(config('form.default_attributes.select')) !!}>{{--
  --}}{!! $slot !!}{{--
  --}}@if (isset($options)){{--
  --}}{!! ConfigHelper::toSelect($options, $prepends, $conditions, $calculates['selected'], $mapping) !!}{{--
  --}}@endif
</select>
