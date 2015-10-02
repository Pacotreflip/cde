<h3>Fotos</h3>
<hr>

@foreach($material->fotos->chunk(3) as $fotoset)
  <div class="row center-block">
    @foreach($fotoset as $foto)
      <div class="col-xs-4">
        <div class="foto thumbnail">
          <a href="/{{ $foto->path }}" target="_blank">
            <img src="/{{ $foto->thumbnail_path }}" class="img-responsive" alt="{{ $foto->nombre }}">
          </a>
          <form action="{{ route('articulos.fotos.delete', [$material, $foto]) }}" method="POST" accept-charset="UTF-8">
            <input name="_method" type="hidden" value="DELETE">
            <input name="_token" type="hidden" value="{{ csrf_token() }}">
            <button type="submit" class="btn btn-xs btn-danger foto-borrar" ><i class="fa fa-times"></i></button>
          </form>
        </div>
      </div>
    @endforeach
  </div>
@endforeach

@unless(count($material->fotos))
  <div class="alert alert-info">Este artículo todavia no tiene fotografias.</div>
@endunless