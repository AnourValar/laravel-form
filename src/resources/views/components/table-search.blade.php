@props(['search'])
<div class="input-group ml-2" style="width: 300px;">
  <div class="input-group-append">
    <span class="input-group-text bg-light"><i class="fas fa-search"></i></span>
  </div>

  <input
    type="text"
    {{ $attributes->merge(['class' => 'form-control']) }}
    placeholder="@lang('Фильтр')"
    data-table-search="{{ $search }}"
  />
  <button type="button" class="btn bg-transparent input-reset" style="margin-left: -37px; z-index: 100;">
    <i class="fa fa-times"></i>
  </button>
</div>
