<h3>Comprobantes</h3>
<hr>

@foreach($transferencia->comprobantes->chunk(3) as $comprobanteset)
  <div class="row center-block">
    @foreach($comprobanteset as $comprobante)
      <div class="col-xs-4">
        <div class="foto thumbnail">
          <a href="/{{ $comprobante->path }}" target="_blank">
            <img src="/{{ $comprobante->thumbnail_path }}" class="img-responsive" alt="{{ $comprobante->nombre }}">
          </a>
          <form action="{{ route('recepciones.comprobantes.delete', [$transferencia, $comprobante]) }}" method="POST" accept-charset="UTF-8">
            <input name="_method" type="hidden" value="DELETE">
            <input name="_token" type="hidden" value="{{ csrf_token() }}">
            <button type="submit" class="btn btn-xs btn-danger foto-borrar" ><i class="fa fa-times"></i></button>
          </form>
        </div>
      </div>
    @endforeach
  </div>
@endforeach

@unless(count($transferencia->comprobantes))
  <div class="alert alert-info">Esta transferencia todavia no tiene comprobantes.</div>
@endunless