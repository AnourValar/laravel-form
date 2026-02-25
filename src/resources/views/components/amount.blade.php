@props(['amount', 'colored' => false, 'default' => 0, 'symbol' => ''])
@inject('numberHelper', AnourValar\LaravelAtom\Helpers\NumberHelper::class)

@php
  $amount = $numberHelper->decodeMultiple($amount);
  $precision = mb_strlen(config('atom.number.multiple')) - 1;

  if ($symbol === true || $symbol === 'true' || $symbol === '1' || $symbol === 1) {
      $symbol = '₽';
  }

  if (isset($amount)) {
    $display = rtrim(rtrim(Number::format($amount, locale: config('app.locale'), precision: $precision), '0'), '.,');
  } else {
    $display = $default;
  }
@endphp

@if ($colored && $amount > 0)
  <span class="text-success">{{ $display }} {{ $symbol }}</span>
@elseif ($colored && $amount < 0)
  <span class="text-danger">{{ $display }} {{ $symbol }}</span>
@else
  {{ $display }} {{ $symbol }}
@endif
