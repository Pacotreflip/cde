Vue.component('screen-comparativa', {

    data: function () {
        return {
            tipo_cambio: 0,
            articulos: [],
            sortKey: '',
            reverse: 1,
            tipoFiltro: 'todos',
            articulosFiltrados: []
        }
    },

    props: ['id'],

    ready: function () {
        this.$http.get('/api/tipos-area/' + this.id + '/comparativa')
            .success(function (response) {
                this.articulos = response.articulos;
                this.tipo_cambio = response.tipo_cambio;
            })
            .error(function (errors) {
                console.error(errors);
            });
    },

    filters: {
        filtro: function (articulos, tipoFiltro) {
            if (tipoFiltro == 'todos') {
                return this.articulosFiltrados = articulos;
            }

            if (tipoFiltro == 'noExistenEnEsteProyecto') {
                return this.articulosQueNoExistenEnEsteProyecto(articulos);
            }

            if (tipoFiltro == 'noExistenEnProyectoComparativo') {
                return this.articulosQueNoExistenEnProyectoComparativo(articulos);
            }

            if (tipoFiltro == 'masCarosEnEsteProyecto') {
                return this.articulosMasCarosEnEsteProyecto(articulos);
            }

            if (tipoFiltro == 'masCarosEnProyectoComparativo') {
                return this.articulosMasCarosEnProyectoComparativo(articulos);
            }
        }
    },

    computed: {
        importeTotalEstimado: function () {
            return this.articulosFiltrados.reduce(function (prev, cur) {
                return prev + cur.importe_estimado_homologado;
            }, 0);
        },

        importeTotalComparativa: function () {
            return this.articulosFiltrados.reduce(function (prev, cur) {
                return prev + cur.importe_comparativa_homologado;
            }, 0);
        }
    },

    methods: {
        sortBy: function (sortKey) {
            this.reverse = this.sortKey == sortKey ? this.reverse * -1 : 1;
            this.sortKey = sortKey;
        },

        articulosQueNoExistenEnEsteProyecto: function (articulos) {
            return this.articulosFiltrados = articulos.filter(function (articulo) {
                return this.articuloNoExisteEnEsteProyecto(articulo);
            }.bind(this));
        },

        articulosQueNoExistenEnProyectoComparativo: function (articulos) {
            return this.articulosFiltrados = articulos.filter(function (articulo) {
                return ! articulo.existe_para_comparativa;
            });
        },

        articulosMasCarosEnEsteProyecto: function (articulos) {
            return this.articulosFiltrados = articulos.filter(function (articulo) {
                return this.articuloExisteEnAmbosProyectos(articulo) &&
                    articulo.precio_estimado_homologado > articulo.precio_comparativa_homologado;
            }.bind(this));
        },

        articulosMasCarosEnProyectoComparativo: function (articulos) {
            return this.articulosFiltrados = articulos.filter(function (articulo) {
                return this.articuloExisteEnAmbosProyectos(articulo) &&
                    articulo.precio_comparativa_homologado > articulo.precio_estimado_homologado;
            }.bind(this));
        },

        articuloExisteEnAmbosProyectos: function (articulo) {
            return articulo.existe_para_comparativa && articulo.cantidad_requerida > 0;
        },

        articuloNoExisteEnEsteProyecto: function (articulo) {
            return articulo.cantidad_requerida == 0;
        }
    }
});
