<div class="Maq-Horas-formControls row">
    <div class="col-xs-3">
        {!! Form::label('tipohora', 'Tipo:') !!}
    </div>
    <div class="col-xs-3">
        {!! Form::label('cantidad', 'Cantidad:') !!}
    </div>
    <div class="col-xs-4">
        {!! Form::label('actividad', 'Actividad:') !!}
    </div>
    <div class="col-xs-2">
    </div>
    <div class="col-xs-3 form-group">
        {!! Form::select('tipohora[]', $tipoHora, null, ['class' => 'form-control']) !!}
    </div>
    <div class="col-xs-3 form-group">
        {!! Form::text('cantidad[]', 0, ['class' => 'form-control']) !!}
    </div>
    <div class="col-xs-4 form-group">
        {!! Form::select('actividad[]', [], null, ['class' => 'form-control']) !!}
    </div>
    <div class="col-xs-2 form-group">
        <a href="" class="Maq-Horas-formControls-removeNew text-danger">Quitar</a>
    </div>
</div>

<div class="Maq-Horas-addNew">
    <a href="">Agregar mas horas</a>
</div>
@section('scripts')
    <script>

        $('.Maq-Horas-addNew').on('click', function(e) {
               e.preventDefault();
               $form = $('.hidden .Maq-Horas-formControls').clone();
               $form.insertBefore(this);
        });

        $('.Maq-Horas-formControls-removeNew').on('click', function(e) {
            e.preventDefault();

            $(this).parent().parent().remove();
        });
    </script>
@stop