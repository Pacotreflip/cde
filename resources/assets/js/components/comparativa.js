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

            if (tipoFiltro == 'soloExistenEnEsteProyecto') {
                return this.articulosQueSoloExistenEnEsteProyecto(articulos);
            }

            if (tipoFiltro == 'soloExistenEnProyectoComparativo') {
                return this.articulosQueSoloExistenEnProyectoComparativo(articulos);
            }

            if (tipoFiltro == 'masCarosEnEsteProyecto') {
                return this.articulosMasCarosEnEsteProyecto(articulos);
            }

            if (tipoFiltro == 'masCarosEnProyectoComparativo') {
                return this.articulosMasCarosEnProyectoComparativo(articulos);
            }

            if (tipoFiltro == 'sinPrecioEnEsteProyecto') {
                return this.articulosSinPrecioEnEsteProyecto(articulos);
            }

            if (tipoFiltro == 'sinPrecioEnProyectoComparativo') {
                return this.articulosSinPrecioEnProyectoComparativo(articulos);
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
        },

        cuentaDeArticulos: function () {
            return this.articulosFiltrados.length;
        }
    },

    methods: {
        sortBy: function (sortKey) {
            this.reverse = this.sortKey == sortKey ? this.reverse * -1 : 1;
            this.sortKey = sortKey;
        },

        sortIsAscending: function (sortKey) {
            return this.reverse == 1 && this.sortKey == sortKey;
        },

        sortIsDescending: function (sortKey) {
            return this.reverse == -1 && this.sortKey == sortKey;
        },

        articulosQueSoloExistenEnEsteProyecto: function (articulos) {
            return this.articulosFiltrados = articulos.filter(function (articulo) {
                return this.articuloSoloExisteEnEsteProyecto(articulo);
            }.bind(this));
        },

        articulosQueSoloExistenEnProyectoComparativo: function (articulos) {
            return this.articulosFiltrados = articulos.filter(function (articulo) {
                return this.articuloSoloExisteEnProyectoComparativo(articulo);
            }.bind(this));
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

        articulosSinPrecioEnEsteProyecto: function (articulos) {
            return this.articulosFiltrados = articulos.filter(function (articulo) {
                return ! articulo.precio_estimado_homologado || articulo.precio_estimado_homologado == 0;
            });
        },

        articulosSinPrecioEnProyectoComparativo: function (articulos) {
            return this.articulosFiltrados = articulos.filter(function (articulo) {
                return ! articulo.precio_comparativa_homologado || articulo.precio_comparativa_homologado == 0;
            });
        },

        articuloExisteEnAmbosProyectos: function (articulo) {
            return articulo.existe_para_comparativa && articulo.cantidad_requerida > 0;
        },

        articuloSoloExisteEnEsteProyecto: function (articulo) {
            return articulo.cantidad_requerida > 0;
        },

        articuloSoloExisteEnProyectoComparativo: function (articulo) {
            return articulo.cantidad_requerida == 0 && articulo.cantidad_comparativa > 0;
        }
    }
});
