@extends ('layout')

@section ('content')
<div class="row">
    <table>
        <tr>
            @foreach($datos as $lista)
            <td style="vertical-align: top; padding: 5px; width: {{$ancho}}%">
                <h3>    {{$lista["name"]}}</h3>
                <div class="row">
                    <div class="col-md-12">
                        <div class="list-group">
                            @if(key_exists("tareas", $lista))
                            @foreach($lista["tareas"] as $tarea)
                            <div class="list-group-item">
                            <h4 class="list-group-item-heading">{{$tarea["name"]}}</h4>
                            
                            @if($tarea["atach"] != "#")
                            <div style="text-align: right">
                                <a href="{{$tarea["atach"]}}" role="button" class="btn btn-success btn-sm"  target="_blank">
                              Ver Detalle
                              <!--<p class="list-group-item-text">...</p>-->
                            </a>
                            </div>
                            @endif
                            </div>
                            @endforeach
                            @endif
                          </div>
                    </div>
                </div>
            </td>
            @endforeach
        </tr>
    </table>
    
</div>
@stop
