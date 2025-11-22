@props(['name', 'value', 'checked'])

<x-input
  type="checkbox"
  :name="$name"
  :value="$value"
  :checked="$checked"
  data-toggle="toggle"
  data-size="sm"
  data-width="60"
  data-onstyle="success"
  data-offstyle="secondary"
  data-on='{{ "<span class=\"fas nav-icon fa-plus\"></span>" }}'
  data-off='{{ "<span class=\"fas nav-icon fa-minus\"></span>" }}'
  {{ $attributes }}
/>
