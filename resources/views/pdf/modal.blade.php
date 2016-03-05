<hr>
<button type="button" class="btn btn-success" data-toggle="modal" data-target="#PDF{{$modulo}}"><i class="fa fa-file-pdf-o"></i> Generar PDF</button>

<div class="modal fade" id="PDF{{$modulo}}" tabindex="-1" role="dialog" aria-labelledby="PDF {{$modulo}}">
    <div class="modal-dialog modal-lg" id="mdialTamanio">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span></button>
                <h4 class="modal-title">{{$titulo}}</h4>
            </div>
            <div class="modal-body modal-lg" style="height: 800px ">
                <iframe src="{{$ruta}}"  frameborder="0" height="100%" width="99.6%"></iframe>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>