@extends('layout')

@section('content')
  <h1>Áreas
    <a href="{{ route('areas.create', Request::has('area') ? ['dentro_de' => Request::get('area')] : []) }}"
      class="btn btn-success pull-right"><i class="fa fa-plus"></i> Nueva Área</a>
  </h1>
  <hr>

  @include('areas.partials.breadcrumb')

  <table class="table table-striped">
    <tbody>
      @foreach($descendientes as $descendiente)
        <tr>
          <td>
            <a href="{{ route('areas.index', ['area='.$descendiente->id]) }}">{{ $descendiente->nombre }}</a>

            <div class="btn-toolbar pull-right">

              <div class="btn-group btn-group-xs">
                <a href="{{ route('areas.edit', [$descendiente]) }}" class="btn btn-primary btn-xs">
                  <span class="fa fa-pencil"></span>
                </a>
              </div>

              <div class="btn-group btn-group-xs">
                <form action="{{ route('areas.update', $descendiente) }}" method="POST" accept-charset="UTF-8">
                  <input type="hidden" name="_method" value="PATCH">
                  <input type="hidden" name="_token" value="{{ csrf_token() }}">

                  <button type="submit" class="btn btn-warning btn-xs">
                    <span class="fa fa-arrow-down"></span>
                  </button>
                  <input type="hidden" name="move_down" value="1">
                </form>
              </div>

              <div class="btn-group btn-group-xs">
                <form action="{{ route('areas.update', $descendiente) }}" method="POST" accept-charset="UTF-8">
                  <input type="hidden" name="_method" value="PATCH">
                  <input type="hidden" name="_token" value="{{ csrf_token() }}">

                  <button type="submit" class="btn btn-warning btn-xs">
                    <span class="fa fa-arrow-up"></span>
                  </button>
                  <input type="hidden" name="move_up" value="1">
                </form>
              </div>
            </div>
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>
  
  @if($area and count($areas_tipo) > 0)
    @include('areas.partials.resumen')
  @endif
@stop
