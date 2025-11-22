@props(['request', 'collection', 'buttons' => null])

<div class="card table-page">
  <div class="card-body">
    <div class="float-right pt-3 pr-3">{{ $collection->appends($request->get())->links() }}</div>
    <div class="pt-3 pr-3 float-right">{!! $buttons !!}</div>
    {!! $slot !!}
    <div class="float-right pr-3 mt-3">{{ $collection->appends($request->get())->links() }}</div>
  </div>
</div>
