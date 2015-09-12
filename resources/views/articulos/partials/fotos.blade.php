<h3>Fotos</h3>
<hr>

@foreach($material->fotos->chunk(3) as $fotoset)
  <div class="row center-block">
    @foreach($fotoset as $foto)
      <div class="col-xs-4">
        <a href="/{{ $foto->path }}" class="thumbnail" target="_blank">
          <img src="/{{ $foto->thumbnail_path }}" class="img-responsive" alt="{{ $foto->nombre }}">
        </a>
      </div>
    @endforeach
  </div>
@endforeach

@unless(count($material->fotos))
  <div class="alert alert-info">Este artículo todavia no tiene fotografias.</div>
@endunless