@props(['value', 'display' => 'id', 'default' => '', 'entity' => null, 'namespace' => 'admin'])

@php
  if ($value) {
      $title = $value->$display;
      if (is_integer($title)) {
          $title = '#' . $title;
      }

      if (! isset($entity)) {
        $entity = explode('\\', get_class($value));
        $entity = array_pop($entity);
        $entity = Str::snake($entity);
      }

      $deleted = false;
      if (in_array(Illuminate\Database\Eloquent\SoftDeletes::class, class_uses($value))) {
          $column = $value->getDeletedAtColumn();
          $deleted = $value->$column;
      }
  }
@endphp

@if (! $value)
  {{ $default }}
@elseif (Auth::user()->can("{$namespace}.{$entity}.show"))
  @php
    $class = $deleted ? 'underline line-through' : 'underline';
  @endphp

  <a href="{{ route($namespace . '.' . $entity . '.edit', [$entity => $value->id]) }}" {{ $attributes->merge(['class' => $class]) }}>
    {!! $slot !!} {{ $title }}
  </a>
@else
  {!! $slot !!} {{ $title }}
@endif
