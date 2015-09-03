@extends('layout')

@section('content')
    <h1>Articulo</h1>
    <hr>

    {!! Form::model($articulo, ['route' => ['articulos.update', $articulo], 'method' => 'PATCH', 'files' => true]) !!}
        <div class="row">
            <div class="col-md-6">
                @include('articulos.partials.edit-fields')

                @include('partials.errors')
            </div>
            <div class="col-md-6 gallery">
                @include('articulos.partials.fotos')
            </div>
        </div>
    {!! Form::close() !!}

    
    <hr>

    <form action="{{ route('articulos.fotos', [$articulo]) }}" 
        class="dropzone" id="dropzone" method="POST" 
        enctype="multipart/form-data">
        {{ csrf_field() }}
    </form>

    <br>
@stop

@section('scripts')
    <script>
        Dropzone.options.dropzone = {
          paramName: "foto",
          dictDefaultMessage: "Arrastra fotografias aqui para subirlas"
        };
    </script>
@stop