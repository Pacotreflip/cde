@extends ('tipos.layout')

@section ('main-content')
  <div id="app">
    <screen-comparativa :id="{{ $tipo->id }}" inline-template>
      <section class="Filtros">
        <h3>Filtros</h3>
        <label class="radio">
          <input type="radio" name="filtros" value="todos" v-model="tipoFiltro"> Todos los Articulos
        </label>
        <label class="radio">
          <input type="radio" name="filtros" value="existenEnEsteProyecto" v-model="tipoFiltro"> Articulos que existen en este proyecto
        </label>
        <label class="radio">
          <input type="radio" name="filtros" value="existenEnProyectoComparativo" v-model="tipoFiltro"> Articulos que existen en proyecto comparativo
        </label>
        <label class="radio">
          <input type="radio" name="filtros" value="soloExistenEnProyectoComparativo" v-model="tipoFiltro"> Articulos que solo existen en proyecto comparativo
        </label>
        <label class="radio">
          <input type="radio" name="filtros" value="soloExistenEnEsteProyecto" v-model="tipoFiltro"> Articulos que solo existen en este proyecto
        </label>
        <label class="radio">
          <input type="radio" name="filtros" value="masCarosEnEsteProyecto" v-model="tipoFiltro"> Articulos mas caros en este proyecto
        </label>
        <label class="radio">
          <input type="radio" name="filtros" value="masCarosEnProyectoComparativo" v-model="tipoFiltro"> Articulos mas caros en proyecto comparativo
        </label>
        <label class="radio">
          <input type="radio" name="filtros" value="sinPrecioEnEsteProyecto" v-model="tipoFiltro"> Articulos sin precio en este proyecto
        </label>
        <label class="radio">
          <input type="radio" name="filtros" value="sinPrecioEnProyectoComparativo" v-model="tipoFiltro"> Articulos sin precio en proyecto comparativo
        </label>
        <label class="radio">
          <input type="radio" name="filtros" value="cotizadosEnEsteProyecto" v-model="tipoFiltro"> Articulos cotizados en este proyecto y que existen en proyecto comparativo
        </label>
        <label class="radio">
          <input type="radio" name="filtros" value="soloCotizadosEnEsteProyecto" v-model="tipoFiltro"> Articulos cotizados a la fecha {{ date('d-m-Y') }} en este proyecto
        </label>
        <label class="radio">
          <input type="radio" name="filtros" value="sinNombre" v-model="tipoFiltro"> Articulos cotizados a la fecha en este proyecto mas articulos unicos en proyecto comparativo
        </label>
      </section>
      <hr>
      <div>
        <table class="table table-condensed table-striped table-bordered">
          <thead>
            <tr>
                <th colspan="4" class="text-center">ESTE PROYECTO</th>
                <th></th>
                <th colspan="5" class="text-center">PROYECTO COMPARATIVO</th>
                <th>DIFERENCIAL</th>
            </tr>
            <tr class="info">
              <th>@{{ cuentaDeArticulos }}</th>
              <th colspan="2" class="text-right">Costo Total (USD):</th>
              <th class="text-right">@{{ importeTotalEstimado | sinDecimales | currency '' }}</th>
              <th>acum</th>
              <th></th>
              <th colspan="2" class="text-right">Costo Total (USD):</th>
              <th class="text-right">@{{ importeTotalComparativa | sinDecimales | currency '' }}</th>
              <th>acum</th>
              <th class="text-right">@{{ importeTotalDiferencia | sinDecimales | currency '' }}</th>
            </tr>
            <tr>
              <th></th>
              <th class="text-right">T.A.: @{{ sumaCantidadRequeridaEsteProyecto }}</th>
              <th colspan="3">Conteo: @{{ cuentaArticulosEsteProyecto }}</th>
              <th></th>
              <th class="text-right">T.A.: @{{ sumaCantidadProyectoComparativo }}</th>
              <th colspan="3">Conteo: @{{ cuentaArticulosProyectoComparativo }}</th>
              <th></th>
            </tr>
            <tr>
                <th>#</th>
                <th class="text-center"><a href="#" @click.prevent="sortBy('cantidad_requerida')">Cantidad</a>
                  <i class="fa fa-sort"
                    v-bind:class="{
                      'fa-sort-asc': sortIsAscending('cantidad_requerida'),
                      'fa-sort-desc': sortIsDescending('cantidad_requerida')
                    }"></i>
                </th>
                <th class="text-center"><a href="#" @click.prevent="sortBy('precio_estimado_homologado')">P.U. (USD)</a>
                  <i class="fa fa-sort"
                    v-bind:class="{
                      'fa-sort-asc': sortIsAscending('precio_estimado_homologado'),
                      'fa-sort-desc': sortIsDescending('precio_estimado_homologado')
                    }"></i>
                </th>
                <th class="text-center"><a href="#" @click.prevent="sortBy('importe_estimado_homologado')">Costo (USD)</a>
                  <i class="fa fa-sort"
                    v-bind:class="{
                      'fa-sort-asc': sortIsAscending('importe_estimado_homologado'),
                      'fa-sort-desc': sortIsDescending('importe_estimado_homologado')
                    }"></i>
                </th>
                <th>Acumulado</th>
                <th class="text-center"><a href="#" @click.prevent="sortBy('material')">Articulo</a>
                  <i class="fa fa-sort"
                    v-bind:class="{
                      'fa-sort-asc': sortIsAscending('material'),
                      'fa-sort-desc': sortIsDescending('material')
                    }"></i>
                </th>
                <th class="text-center"><a href="#" @click.prevent="sortBy('cantidad_comparativa')">Cantidad</a>
                  <i class="fa fa-sort"
                    v-bind:class="{
                      'fa-sort-asc': sortIsAscending('cantidad_comparativa'),
                      'fa-sort-desc': sortIsDescending('cantidad_comparativa')
                    }"></i>
                </th>
                <th class="text-center"><a href="#" @click.prevent="sortBy('precio_comparativa_homologado')">P.U. (USD)</a>
                  <i class="fa fa-sort"
                    v-bind:class="{
                      'fa-sort-asc': sortIsAscending('precio_comparativa_homologado'),
                      'fa-sort-desc': sortIsDescending('precio_comparativa_homologado')
                    }"></i>
                </th>
                <th class="text-center"><a href="#" @click.prevent="sortBy('importe_comparativa_homologado')">Costo (USD)</a>
                  <i class="fa fa-sort"
                    v-bind:class="{
                      'fa-sort-asc': sortIsAscending('importe_comparativa_homologado'),
                      'fa-sort-desc': sortIsDescending('importe_comparativa_homologado')
                    }"></i>
                </th>
                <th>Acumulado</th>
                <th class="text-center"><a href="#" @click.prevent="sortBy('diferencia_costo_homologado')">Costo (USD)</a>
                  <i class="fa fa-sort"
                    v-bind:class="{
                      'fa-sort-asc': sortIsAscending('diferencia_costo_homologado'),
                      'fa-sort-desc': sortIsDescending('diferencia_costo_homologado')
                    }"></i>
                </th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="articulo in articulos | orderBy sortKey reverse | filtro tipoFiltro">
              <th>@{{ $index+1 }}</th>
              <td class="text-right">@{{ articulo.cantidad_requerida }}</td>
              <td class="text-right" v-bind:class="{ warning: tipoFiltro == 'masCarosEnEsteProyecto' || tipoFiltro == 'masCarosEnProyectoComparativo' }">
                @{{ articulo.precio_estimado_homologado | sinDecimales | currency '' }}
              </td>
              <td class="text-right">@{{ articulo.importe_estimado_homologado | sinDecimales | currency '' }}</td>
              <td class="text-right">@{{ costoAcumuladoCorrienteEsteProyecto($index) | sinDecimales | currency '' }}</td>
              <td><a href="@{{ articulo.url }}">@{{ articulo.material }}</a></td>
              <td class="text-right">@{{ articulo.cantidad_comparativa }}</td>
              <td class="text-right" v-bind:class="{ warning: tipoFiltro == 'masCarosEnEsteProyecto' || tipoFiltro == 'masCarosEnProyectoComparativo' }">
                @{{ articulo.precio_comparativa_homologado | sinDecimales | currency '' }}
              </td>
              <td class="text-right">@{{ articulo.importe_comparativa_homologado | sinDecimales | currency '' }}</td>
              <td class="text-right">@{{ costoAcumuladoCorrienteProyectoComparativo($index) | sinDecimales | currency '' }}</td>
              <td class="text-right">@{{ articulo.diferencia_costo_homologado | sinDecimales | currency '' }}</td>
            </tr>
          </tbody>
          <tfoot>
            <tr class="info">
              <th>@{{ cuentaDeArticulos }}</th>
              <th colspan="2" class="text-right">Costo Total (USD):</th>
              <th class="text-right">@{{ importeTotalEstimado | sinDecimales | currency '' }}</th>
              <th class="text-right">@{{ importeTotalEstimado | sinDecimales | currency '' }}</th>
              <th></th>
              <th colspan="2" class="text-right">Costo Total (USD):</th>
              <th class="text-right">@{{ importeTotalComparativa | sinDecimales | currency '' }}</th>
              <th class="text-right">@{{ importeTotalComparativa | sinDecimales | currency '' }}</th>
              <th class="text-right">@{{ importeTotalDiferencia | sinDecimales | currency '' }}</th>
            </tr>
          </tfoot>
        </table>
      </div>
    </screen-comparativa>
  </div>
@stop
