@extends ('layouts.default')

@section ('content')

    <div class="col-md-12">
        <p>
            {!! link_to_route('almacenes.create', 'Nuevo almacen', [], ['class' => 'btn btn-primary']) !!}
        </p>

        <div class="panel panel-success">
            <div class="panel-heading">
                <h3 class="panel-title text-center">Almacenes</h3>
            </div>
            <ul class="list-group">
                @forelse($almacenes as $almacen)
                    {!! link_to_route('almacenes.show', $almacen->present()->descripcionCompleta, [$almacen->id_almacen], ['class' => 'list-group-item']) !!}
                @empty
                    <li class="list-group-item text-danger">No existen almacenes registrados</li>
                @endforelse
            </ul>
        </div>
    </div>

    {!! $almacenes->links() !!}
@stop