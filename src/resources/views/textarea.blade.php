@php
  $calculates = $calculate(($errors ?? new \Illuminate\Support\ViewErrorBag([])), old(), $slot);
@endphp

<textarea {!! $attributes->class([config('form.error') => $calculates['hasError']])->merge(config('form.default_attributes.textarea'))->merge($merge) !!}>{{--
  --}}{!! $calculates['value'] !!}{{--
--}}</textarea>
