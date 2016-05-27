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
                            <a href="{{$tarea["atach"]}}" class="list-group-item" target="_blank">
                              <h4 class="list-group-item-heading">{{$tarea["name"]}}</h4>
                              <!--<p class="list-group-item-text">...</p>-->
                            </a>
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
