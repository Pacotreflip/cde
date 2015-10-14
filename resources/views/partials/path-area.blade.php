<ol class="breadcrumb">
  @foreach($area->getAncestors() as $path)
      <li>{{ $path->nombre }}</li>
  @endforeach
  <li>{{ $area->nombre }}</li>
</ol>