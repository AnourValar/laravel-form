@props(['url', 'width' => '30', 'height' => '30'])

@php
  $extension = mb_strtolower(pathinfo($url, PATHINFO_EXTENSION));
@endphp

@if (in_array($extension, ['jpeg', 'jpg', 'png', 'svg', 'webp', 'gif', 'avif']))
  <img src="{{ $url }}" style="max-width: {{ $width }}px; max-height: {{ $height }}px;" {{ $attributes }} />
@elseif (in_array($extension, ['mp4', 'mov', 'mkv', 'avi']))
 <span style="max-width: {{ $width }}px; max-height: {{ $height }}px;" {{ $attributes }}><i class="fa-solid fa-film"></i></span>
@elseif (in_array($extension, ['pdf']))
 <span style="max-width: {{ $width }}px; max-height: {{ $height }}px;" {{ $attributes }}><i class="fa-regular fa-file-pdf"></i></span>
@else
 <span style="max-width: {{ $width }}px; max-height: {{ $height }}px;" {{ $attributes }}><i class="fa-regular fa-file"></i></span>
@endif
