@props(['value', 'entity' => null, 'namespace' => 'admin'])

@php
  if (is_object($value)) {
      if (! isset($entity)) {
        $entity = $value->getMorphClass();
      }

      $value = $value->getKey();
  }
@endphp

@if ($value && Auth::user()->can("{$namespace}.{$entity}.show"))
  <a
    href="{{ route($namespace . '.' . $entity . '.edit', [$entity => $value]) }}"
    class="btn btn-outline-primary"
    title="@lang('Карточка')"
    {{ $attributes }}
  >
    &nbsp;<i class="fa-solid fa-pen"></i>&nbsp;
  </a>
@endif
