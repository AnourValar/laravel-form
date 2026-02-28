@props(['value', 'default' => 0, 'symbol' => '', 'trim' => true, 'colored' => false])
@inject('numberHelper', AnourValar\LaravelAtom\Helpers\NumberHelper::class)

@php
  $display = isset($value) ? $numberHelper->formatMultiple($value, null, $trim) : $default;
  $compare = isset($value) ? bccomp($value, 0) : null;
@endphp

@if ($colored && $compare === 1)
  <span class="text-success">{{ $display }}{{ $symbol }}</span>
@elseif ($colored && $compare === -1)
  <span class="text-danger">{{ $display }}{{ $symbol }}</span>
@elseif (isset($compare))
  {{ $display }}{{ $symbol }}
@else
  {{ $display }}
@endif
