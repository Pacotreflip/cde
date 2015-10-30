@extends('layout')

@section('content')
  <h1>Clasificadores de Artículo
    <a href="{{ route('clasificadores.create', Request::has('clasificador') ? 
      ['dentro_de' => Request::get('clasificador')] : []) }}" class="btn btn-success pull-right">
      <i class="fa fa-plus"></i> Nuevo Clasificador</a>
  </h1>
  <hr>

  @include('clasificadores.partials.breadcrumb')

  <table class="table table-striped" data-link="row">
    <tbody class="rowlink">
      @foreach($descendientes as $descendiente)
        <tr>
          <td>
            <a href="{{ route('clasificadores.index', ['clasificador='.$descendiente->id]) }}">
              {{ $descendiente->nombre }}
            </a>

            <div class="btn-toolbar pull-right" role="toolbar" aria-label="...">
              <div class="btn-group btn-group-xs" role="group" aria-label="...">
                <a href="{{ route('clasificadores.edit', [$descendiente]) }}" class="btn btn-primary btn-xs">
                    <span class="fa fa-pencil"></span>
                </a>
              </div>
              <div class="btn-group btn-group-xs" role="group" aria-label="...">
                {!! Form::open(['route' => ['clasificadores.update', $descendiente], 'method' => 'PATCH']) !!}
                  <button type="submit" class="btn btn-warning btn-xs">
                    <span class="fa fa-arrow-down"></span>
                  </button>
                  <input type="hidden" id="move_down" name="move_down" value="1">
                {!! Form::close() !!}
              </div>
              <div class="btn-group btn-group-xs" role="group" aria-label="...">
                {!! Form::open(['route' => ['clasificadores.update', $descendiente], 'method' => 'PATCH']) !!}
                  <button type="submit" class="btn btn-warning btn-xs pull-right">
                    <span class="fa fa-arrow-up"></span>
                  </button>
                  <input type="hidden" id="move_up" name="move_up" value="1">
                {!! Form::close() !!}
              </div>
            </div>
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>
  @include('partials.errors')
@stop