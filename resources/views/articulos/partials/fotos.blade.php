<h3>Fotos</h3>
<hr>
@foreach($material->fotos->chunk(3) as $fotoset)
    <div class="row text-center">
        @foreach($fotoset as $foto)
            <div class="col-xs-4 gallery__image">
                <a href="/{{ $foto->path }}" target="_blank">
                    <img src="/{{ $foto->thumbnail_path }}" alt="{{ $foto->nombre }}">
                </a>
            </div>
        @endforeach
    </div>
@endforeach
@unless(count($material->fotos))
    <div class="alert alert-info">Actualmente este articulo no tiene fotografias.</div>
@endunless