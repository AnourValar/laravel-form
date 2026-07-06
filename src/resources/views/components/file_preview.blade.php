@props(['url', 'width' => '35', 'height' => '35'])

@php
  $extension = mb_strtolower(pathinfo($url, PATHINFO_EXTENSION));
  $extension = explode('?', $extension)[0];
@endphp

@if (in_array($extension, ['jpeg', 'jpg', 'png', 'svg', 'webp', 'gif', 'avif']))
  <img src="{{ $url }}" style="max-width: {{ $width }}px; max-height: {{ $height }}px;" {{ $attributes }} />
@elseif (in_array($extension, ['mp4', 'mov', 'mkv', 'avi']))
 <span {{ $attributes }}><i class="fa-solid fa-film" style="font-size: {{ (int) ($height * 0.6) }}px;"></i></span>
@elseif (in_array($extension, ['pdf']))
 <span {{ $attributes }}><i class="fa-regular fa-file-pdf" style="font-size: {{ (int) ($height * 0.6) }}px;"></i></span>
@else
 <span {{ $attributes }}><i class="fa-regular fa-file" style="font-size: {{ (int) ($height * 0.6) }}px;"></i></span>
@endif
