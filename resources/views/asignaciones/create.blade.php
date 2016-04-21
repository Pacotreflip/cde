@extends('layout')
@section('content')
@foreach($destinos->items() as $d)
{{$d->id }}

{{$d->nombre}}

{{$d->cantidad_requerida($articulo->id_material)}}
<br>
@endforeach
@stop