@extends ('layouts.default')

@section ('content')
    <h1>{{ $currentUser->present()->fullName }}</h1>

    <div class="row">
        <div class="col-md-2">Usuario</div>
        <div class="col-md-4">{{ $currentUser->usuario }}</div>
    </div>
    <div class="row">
        <div class="col-md-2">Correo</div>
        <div class="col-md-4">{{ $currentUser->correo }}</div>
    </div>
    <div class="row">
        <div class="col-md-2">Extensión</div>
        <div class="col-md-4">{{ $currentUser->extension }}</div>
    </div>
    <div class="row">
        <div class="col-md-2">Fecha de nacimiento</div>
        <div class="col-md-4">{{ $currentUser->present()->fechaNacimiento }}</div>
    </div>
    <div class="row">
        <div class="col-md-2">Ubicación</div>
        <div class="col-md-4">{{ $currentUser->ubicacion->ubicacion }}</div>
    </div>
    <div class="row">
        <div class="col-md-2">Empresa</div>
        <div class="col-md-4">{{ $currentUser->empresa->empresa }}</div>
    </div>
    <div class="row">
        <div class="col-md-2">Departamento</div>
        <div class="col-md-4">{{ $currentUser->departamento->departamento }}</div>
    </div>
    <div class="row">
        <div class="col-md-2">Titulo</div>
        <div class="col-md-4">{{ $currentUser->titulo->titulo }}</div>
    </div>
@stop