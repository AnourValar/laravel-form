@php
  $calculates = $calculate(($errors ?? new \Illuminate\Support\ViewErrorBag([])), old());
@endphp

<select {!! $attributes->class([config('form.error') => $calculates['hasError']])->merge(config('form.default_attributes.select'))->merge($merge) !!}>{{--
  --}}{!! $slot !!}{{--
  --}}{!! isset($prepends) ? ConfigHelper::toSelect($prepends, $calculates['selected']) : '' !!}{{--
  --}}{!! isset($options) ? ConfigHelper::toSelect($options, $calculates['selected'], $conditions, $mapping) : '' !!}{{--
  --}}{!! isset($appends) ? ConfigHelper::toSelect($appends, $calculates['selected']) : '' !!}{{--
  --}}</select>
