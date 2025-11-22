@props(['number', 'decimals' => null, 'symbol' => '', 'precision' => 2])

@if (isset($number))
  @php
    if (is_null($decimals)) {
        $decimals = $number == (int) $number ? 0 : $precision;
    }
  @endphp
  {{ Number::format($number, locale: config('app.locale'), precision: $decimals) }}{{ $symbol }}
@endif
