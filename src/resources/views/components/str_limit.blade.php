@props(['string', 'limit' => 100])

@if (isset($string) && mb_strlen($string) > $limit)
  <div data-toggle="tooltip" title="{{ $string }}">{{ Str::limit($string, $limit) }}</div>
@elseif (isset($string))
  {{ $string }}
@endif
