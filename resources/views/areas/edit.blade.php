@extends('layout')

@section('content')
    @include('areas.partials.breadcrumb', ['ancestros' => $area->getAncestors()])

    <h1>Area</h1>
    <hr>

    {!! Form::model($area, ['route' => ['areas.update', $area], 'method' => 'PATCH']) !!}
        @include('areas.partials.edit-fields')
    {!! Form::close() !!}

    <hr>

    {!! Form::open(['route' => ['areas.delete', $area], 'method' => 'DELETE']) !!}
        <div class="form-group">
            {!! Form::submit('Borrar esta area', ['class' => 'btn btn-danger pull-right']) !!}
        </div>
    {!! Form::close() !!}
@stop