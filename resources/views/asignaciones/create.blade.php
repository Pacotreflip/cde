@extends('layout')
@section('content')
@foreach($destinos->items() as $d)
{{dd($d->cantidad_requerida($articulo->id_material))}}
@endforeach
@stop