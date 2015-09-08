@extends('layout')

@section('content')
    <h1>Areas
        <a href="{{ route('areas.create', Request::has('area') ? ['dentro_de' => Request::get('area')] : []) }}"
           class="btn btn-success pull-right"><i class="fa fa-plus"></i> Nueva Area</a>
    </h1>
    <hr>

    @include('areas.partials.breadcrumb')

    <table class="table table-striped table-hover rowlink" data-link="row">
        <tbody>
            @foreach($descendientes as $descendiente)
                <tr>
                    <td>
                        <a href="{{ route('areas.index', ['area='.$descendiente->id]) }}">{{ $descendiente->nombre }}</a>
                        <a href="{{ route('areas.edit', [$descendiente]) }}" class="pull-right"><i class="fa fa-fw fa-pencil text-primary"></i></a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@stop