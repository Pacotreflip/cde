@extends ('tipos.layout')

@section ('main-content')
  <div id="app">
    <screen-comparativa inline-template>
      <div>
        <table class="table table-condensed table-striped">
          <thead>
            <tr>
                <th colspan="4" class="text-center">ESTE PROYECTO</th>
                <th></th>
                <th colspan="4" class="text-center">PROYECTO COMPARATIVO</th>
            </tr>
            <tr>
                <th>#</th>
                <th class="text-center"><a href="#" @click="sortBy('cantidad_requerida')">Cantidad</a>
                  <i class="fa fa-sort" v-bind:class="{ 'fa-sort-asc': reverse == 1 && sortKey == 'cantidad_requerida', 'fa-sort-desc': reverse == -1 && sortKey == 'cantidad_requerida' }"></i>
                </th>
                <th class="text-center"><a href="#" @click="sortBy('precio_estimado_homologado')">P.U. (USD)</a>
                  <i class="fa fa-sort" v-bind:class="{ 'fa-sort-asc': reverse == 1 && sortKey == 'precio_estimado_homologado', 'fa-sort-desc': reverse == -1 && sortKey == 'precio_estimado_homologado' }"></i>
                </th>
                <th class="text-center"><a href="#" @click="sortBy('importe_estimado_homologado')">Importe (USD)</a>
                  <i class="fa fa-sort" v-bind:class="{ 'fa-sort-asc': reverse == 1 && sortKey == 'importe_estimado_homologado', 'fa-sort-desc': reverse == -1 && sortKey == 'importe_estimado_homologado' }"></i>
                </th>
                <th class="text-center"><a href="#" @click="sortBy('material')">Articulo</a>
                  <i class="fa fa-sort" v-bind:class="{ 'fa-sort-asc': reverse == 1 && sortKey == 'material', 'fa-sort-desc': reverse == -1 && sortKey == 'material' }"></i>
                </th>
                <th class="text-center"><a href="#" @click="sortBy('cantidad_comparativa')">Cantidad</a>
                  <i class="fa fa-sort" v-bind:class="{ 'fa-sort-asc': reverse == 1 && sortKey == 'cantidad_comparativa', 'fa-sort-desc': reverse == -1 && sortKey == 'cantidad_comparativa' }"></i>
                </th>
                <th class="text-center"><a href="#" @click="sortBy('precio_comparativa_homologado')">P.U. (USD)</a>
                  <i class="fa fa-sort" v-bind:class="{ 'fa-sort-asc': reverse == 1 && sortKey == 'precio_comparativa_homologado', 'fa-sort-desc': reverse == -1 && sortKey == 'precio_comparativa_homologado' }"></i>
                </th>
                <th class="text-center"><a href="#" @click="sortBy('importe_comparativa_homologado')">Importe (USD)</a>
                  <i class="fa fa-sort" v-bind:class="{ 'fa-sort-asc': reverse == 1 && sortKey == 'importe_comparativa_homologado', 'fa-sort-desc': reverse == -1 && sortKey == 'importe_comparativa_homologado' }"></i>
                </th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="articulo in articulos | orderBy sortKey reverse">
              <th>@{{ $index+1 }}</th>
              <td class="text-right">@{{ articulo.cantidad_requerida }}</td>
              <td class="text-right">@{{ articulo.precio_estimado_homologado | currency ''  }}</td>
              <td class="text-right">@{{ articulo.importe_estimado_homologado | currency ''  }}</td>
              <td><a href="@{{ articulo.url }}">@{{ articulo.material }}</a></td>
              <td class="text-right">@{{ articulo.cantidad_comparativa }}</td>
              <td class="text-right">@{{ articulo.precio_comparativa_homologado | currency '' }}</td>
              <td class="text-right">@{{ articulo.importe_comparativa_homologado | currency '' }}</td>
            </tr>
          </tbody>
          <tfoot>
            <tr class="info">
              <th></th>
              <th colspan="2" class="text-right">Total (USD):</th>
              <th class="text-right">@{{ importeTotalEstimado | currency '' }}</th>
              <th></th>
              <th colspan="2" class="text-right">Total (USD):</th>
              <th class="text-right">@{{ importeTotalComparativa | currency '' }}</th>
              <th></th>
            </tr>
          </tfoot>
        </table>
      </div>
    </screen-comparativa>
        {{-- <div class="well" data-spy="affix" data-offset-top="60" data-offset-bottom="10">
            <div class="info text-right">
                <span class="text-right">Total (USD):</span>
                <span class="text-right">{{ round($importe_total, 2) }}</span>
                <span class="text-right">Total (USD):</span>
                <span class="text-right">{{ round($importe_total_comparativa, 2) }}</span>
            </div>
        </div> --}}      
  </div>
@stop
