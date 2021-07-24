@php
  $calculates = $calculate($errors, old());
@endphp

<input {!! $attributes->class([config('form.error') => $calculates['hasError']])->merge(config('form.default_attributes.input')) !!} />
