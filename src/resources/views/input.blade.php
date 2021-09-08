@php
  $calculates = $calculate(($errors ?? new \Illuminate\Support\ViewErrorBag([])), old());
@endphp

<input {!! $attributes->class([config('form.error') => $calculates['hasError']])->merge(config('form.default_attributes.input'))->merge($merge) !!} />
