@props(['request', 'route', 'open' => false])

<form method="POST" action="{{ route($route, ['sort' => $request->sort()]) }}" id="form-filters">
  @csrf
  <div class="card card-primary {{ $request->hasFilters() || $open ? '' : 'collapsed-card' }}">
    <div class="card-header" data-card-widget="collapse">
      <h3 class="card-title">@lang('Фильтры')</h3>
      <div class="card-tools">
        <button type="button" class="btn btn-tool" data-card-widget="collapse">
          <i class="fas {{ $request->hasFilters() || $open ? 'fa-minus' : 'fa-plus' }}"></i>
        </button>
        {{-- <button type="button" class="btn btn-tool" data-card-widget="remove">
            <i class="fas fa-times"></i>
          </button> --}}
      </div>
    </div>
    <div class="card-body">
      {!! $slot !!}
    </div>
    <div class="card-footer">
      <input type="submit" class="btn btn-primary" value="@lang('Применить')" />
      <input type="submit" class="btn btn-secondary filters-reset" value="@lang('Сбросить')" />
    </div>
  </div>
</form>
