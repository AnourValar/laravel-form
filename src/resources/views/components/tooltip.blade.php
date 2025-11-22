@props(['title', 'tag' => 'span'])

{{-- prettier-ignore-start --}}
<{!! $tag !!} data-toggle="tooltip" title="{{ $title }}" {{ $attributes }}>
  {!! $slot !!} <i class="far fa-question-circle"></i>
</{!! $tag !!}>
{{-- prettier-ignore-end --}}
