@props(['route', 'request', 'attribute'])

@if ($request->sort($attribute) == 'DESC')
  <a class="badge badge-primary" href="{{ route($route, ['filter' => $request->filter(), 'relation' => $request->relation(), 'scope' => $request->scope(), 'sort' => [$attribute => 'ASC']]) }}">
    <i class="fa fa-angle-down"></i>
  </a>
@elseif ($request->sort($attribute) == 'ASC')
  <a class="badge badge-primary" href="{{ route($route, ['filter' => $request->filter(), 'relation' => $request->relation(), 'scope' => $request->scope(), 'sort' => [$attribute => 'DESC']]) }}">
    <i class="fa fa-angle-up"></i>
  </a>
@else
  <a href="{{ route($route, ['filter' => $request->filter(), 'relation' => $request->relation(), 'scope' => $request->scope(), 'sort' => [$attribute => 'DESC']]) }}">
    <i class="fa fa-angle-down"></i>
  </a>
@endif
