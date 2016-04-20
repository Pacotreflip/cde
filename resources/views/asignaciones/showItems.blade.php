@extends('layout')

@section('content')
<h1>Nueva Asignación de Artículos</h1>
<hr>
@include('partials.search-form')
<div class="section">
<div class="col-md-3">
    @if(isset($currarea))
    <a href="{{route('asignar.showAllItems')}}"><h4><strong>TODOS LOS ALMACENES</strong></h4></a>

    @else
    <h4><strong>SELECCIONAR ALMACÉN</strong></h4>
    @endif
    <ul>
        @foreach($areasraiz as $area)
        @if($area->cantidad_almacenada() > 0)
        <li class="area"><a href="{{route('asignar.showItems', ['id' => $area->id])}}">{{$area->nombre}} </a><i class="fa fa-chevron-circle-right fa-lg"></i>
            <ul class="children">
                @foreach($area->areas_hijas as $hija)
                @if($hija->cantidad_almacenada > 0)
                <li><a href="./{{$hija->id}}">{{$hija->nombre}} </a></li>
                @endif
                @endforeach
            </ul>  
        </li>
        @endif
        @endforeach
    </ul>
</div>
<div class="col-md-9">
    <table class="table table-hover">
        <h4><strong>{{isset($currarea) ? $currarea->ruta : 'ARTÍCULOS ALMACENADOS' }}</strong></h4>
        <thead>
            <tr>
                <th>Area</th>
                <th>#Parte</th>
                <th>Descripción</th>
                <th>Unidad</th>
                <th>Almacenados</th>
                <th>Esperados</th>
                <th>Asignados</th>
                <th>Asignar a Destino(s)</th>
            </tr>
        </thead>

        <tbody>
            @foreach($articulos as $articulo)
           <?php $areaArt = \Ghi\Equipamiento\Areas\Area::find($articulo->id_area) ?>
            <tr>
                <td>{{$areaArt->ruta}}</td>
                <td>{{ $articulo->material->numero_parte}}</td>
                <td><strong>{{ $articulo->material->descripcion }}</strong></td>
                <td>{{ $articulo->material->unidad }}</td>
                <td>{{ $articulo->cantidad_existencia }}</td>
                <td>{{ $articulo->material->cantidad_esperada($articulo->id_area) }}</td>
                <td>{{ $articulo->material->cantidad_asignada($articulo->id_area) }}</td>
                <td><a href="{{route('asignaciones.create', ['area' => $articulo->id_area, 'id' => $articulo->material->id_material])}}"><i class=" btn btn-primary fa fa-sitemap"></i></a></td>                
            </tr>
            @endforeach
        </tbody>    
    </table>
    {!! $articulos->render() !!}
      @if(isset($currarea))
    <a style="float: right" class="btn btn-primary" href="{{route('asignar.showAllItems')}}"><i class="fa fa-reply fa-lg"></i> Todos los Almacenes</a>
    @endif
    <br>
    <br>
   
</div>
<hr>
@stop
@section('scripts')
<script>
$(document).ready(function(){
    $('ul li > ul').slideUp();   
});

$('ul li.area').click(function(e) {
    $(this).children('ul.children').slideToggle(300);
});
</script>
@stop