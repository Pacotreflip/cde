@extends ('layouts.default')

@section ('content')
    <div class="row">
        <div class="col-md-6 col-md-offset-3">

            <div class="panel panel-success">
                <div class="panel-heading">
                    <h3 class="panel-title text-center">Listado de Obras</h3>
                </div>

                <ul class="list-group">
                    @forelse ($obras as $obra)
                        {!! link_to_route('context_path', $obra->nombre, [$obra->getConnectionName(), $obra->id_obra], ['class' => 'list-group-item']) !!}
                    @empty
                        <li class="list-group-item text-danger">No se encontraron obras asignadas a su cuenta</li>
                    @endforelse
                </ul>
            </div>

        </div>
    </div>
@stop