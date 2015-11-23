@extends ('tipos.layout')

@section ('main-content')
  <div id="app">
    <screen-comparativa :id="{{ $tipo->id }}" inline-template>
      <section class="Filtros">
        <h3>Filtros</h3>
        <label class="radio-inline">
          <input type="radio" name="filtros" value="todos" v-model="tipoFiltro"> Todos los Articulos
        </label>
        <label class="radio-inline">
          <input type="radio" name="filtros" value="existenEnEsteProyecto" v-model="tipoFiltro"> Articulos que existen en este proyecto
        </label>
        <label class="radio-inline">
          <input type="radio" name="filtros" value="soloExistenEnProyectoComparativo" v-model="tipoFiltro"> Articulos que solo existen en proyecto comparativo
        </label>
        <label class="radio-inline">
          <input type="radio" name="filtros" value="masCarosEnEsteProyecto" v-model="tipoFiltro"> Articulos mas caros en este proyecto
        </label>
        <label class="radio-inline">
          <input type="radio" name="filtros" value="masCarosEnProyectoComparativo" v-model="tipoFiltro"> Articulos mas caros en proyecto comparativo
        </label>
        <label class="radio-inline">
          <input type="radio" name="filtros" value="sinPrecioEnEsteProyecto" v-model="tipoFiltro"> Articulos sin precio en este proyecto
        </label>
        <label class="radio-inline">
          <input type="radio" name="filtros" value="sinPrecioEnProyectoComparativo" v-model="tipoFiltro"> Articulos sin precio en proyecto comparativo
        </label>
        <label class="radio-inline">
          <input type="radio" name="filtros" value="soloExistenEnEsteProyecto" v-model="tipoFiltro"> Articulos que solo existen en este proyecto
        </label>
        <label class="radio-inline">
          <input type="radio" name="filtros" value="cotizadosEnEsteProyecto" v-model="tipoFiltro"> Articulos cotizados en este proyecto
        </label>
      </section>
      <hr>
      <div>
        <table class="table table-condensed table-striped table-bordered">
          <thead>
            <tr>
                <th colspan="4" class="text-center">ESTE PROYECTO</th>
                <th></th>
                <th colspan="3" class="text-center">PROYECTO COMPARATIVO</th>
                <th>DIFERENCIAL</th>
            </tr>
            <tr class="info">
                <th>@{{ cuentaDeArticulos }}</th>
                <th colspan="2" class="text-right">Costo Total (USD):</th>
                <th class="text-right">@{{ importeTotalEstimado | sinDecimales | currency '' }}</th>
                <th></th>
                <th colspan="2" class="text-right">Costo Total (USD):</th>
                <th class="text-right">@{{ importeTotalComparativa | sinDecimales | currency '' }}</th>
                <th class="text-right">@{{ Math.abs(importeTotalEstimado - importeTotalComparativa) | sinDecimales | currency '' }}</th>
            </tr>
            <tr>
                <th>#</th>
                <th class="text-center"><a href="#" @click="sortBy('cantidad_requerida')">Cantidad</a>
                  <i class="fa fa-sort"
                    v-bind:class="{
                      'fa-sort-asc': sortIsAscending('cantidad_requerida'),
                      'fa-sort-desc': sortIsDescending('cantidad_requerida')
                    }"></i>
                </th>
                <th class="text-center"><a href="#" @click="sortBy('precio_estimado_homologado')">P.U. (USD)</a>
                  <i class="fa fa-sort"
                    v-bind:class="{
                      'fa-sort-asc': sortIsAscending('precio_estimado_homologado'),
                      'fa-sort-desc': sortIsDescending('precio_estimado_homologado')
                    }"></i>
                </th>
                <th class="text-center"><a href="#" @click="sortBy('importe_estimado_homologado')">Costo (USD)</a>
                  <i class="fa fa-sort"
                    v-bind:class="{
                      'fa-sort-asc': sortIsAscending('importe_estimado_homologado'),
                      'fa-sort-desc': sortIsDescending('importe_estimado_homologado')
                    }"></i>
                </th>
                <th class="text-center"><a href="#" @click="sortBy('material')">Articulo</a>
                  <i class="fa fa-sort"
                    v-bind:class="{
                      'fa-sort-asc': sortIsAscending('material'),
                      'fa-sort-desc': sortIsDescending('material')
                    }"></i>
                </th>
                <th class="text-center"><a href="#" @click="sortBy('cantidad_comparativa')">Cantidad</a>
                  <i class="fa fa-sort"
                    v-bind:class="{
                      'fa-sort-asc': sortIsAscending('cantidad_comparativa'),
                      'fa-sort-desc': sortIsDescending('cantidad_comparativa')
                    }"></i>
                </th>
                <th class="text-center"><a href="#" @click="sortBy('precio_comparativa_homologado')">P.U. (USD)</a>
                  <i class="fa fa-sort"
                    v-bind:class="{
                      'fa-sort-asc': sortIsAscending('precio_comparativa_homologado'),
                      'fa-sort-desc': sortIsDescending('precio_comparativa_homologado')
                    }"></i>
                </th>
                <th class="text-center"><a href="#" @click="sortBy('importe_comparativa_homologado')">Costo (USD)</a>
                  <i class="fa fa-sort"
                    v-bind:class="{
                      'fa-sort-asc': sortIsAscending('importe_comparativa_homologado'),
                      'fa-sort-desc': sortIsDescending('importe_comparativa_homologado')
                    }"></i>
                </th>
                <th></th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="articulo in articulos | orderBy sortKey reverse | filtro tipoFiltro">
              <th>@{{ $index+1 }}</th>
              <td class="text-right">@{{ articulo.cantidad_requerida }}</td>
              <td class="text-right" v-bind:class="{ warning: tipoFiltro == 'masCarosEnEsteProyecto' || tipoFiltro == 'masCarosEnProyectoComparativo' }">@{{ articulo.precio_estimado_homologado | sinDecimales | currency '' }}</td>
              <td class="text-right">@{{ articulo.importe_estimado_homologado | sinDecimales | currency '' }}</td>
              <td><a href="@{{ articulo.url }}">@{{ articulo.material }}</a></td>
              <td class="text-right">@{{ articulo.cantidad_comparativa }}</td>
              <td class="text-right" v-bind:class="{ warning: tipoFiltro == 'masCarosEnEsteProyecto' || tipoFiltro == 'masCarosEnProyectoComparativo' }">@{{ articulo.precio_comparativa_homologado | sinDecimales | currency '' }}</td>
              <td class="text-right">@{{ articulo.importe_comparativa_homologado | sinDecimales }}</td>
              <td class="text-right">@{{ Math.abs(articulo.importe_estimado_homologado - articulo.importe_comparativa_homologado) | sinDecimales | currency '' }}</td>
            </tr>
          </tbody>
          <tfoot>
            <tr class="info">
              <th>@{{ cuentaDeArticulos }}</th>
              <th colspan="2" class="text-right">Costo Total (USD):</th>
              <th class="text-right">@{{ importeTotalEstimado | sinDecimales | currency '' }}</th>
              <th></th>
              <th colspan="2" class="text-right">Costo Total (USD):</th>
              <th class="text-right">@{{ importeTotalComparativa | sinDecimales | currency '' }}</th>
              <th class="text-right">@{{ Math.abs(importeTotalEstimado - importeTotalComparativa) | sinDecimales | currency '' }}</th>
            </tr>
          </tfoot>
        </table>
      </div>
    </screen-comparativa>
  </div>
@stop
