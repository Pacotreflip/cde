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
                this.articulosFiltrados = articulos;
                return articulos;
            }

            if (tipoFiltro == 'noExistenEnEsteProyecto') {
                return this.articulosFiltrados = articulos.filter(function (articulo) {
                    return ! articulo.existe_para_comparativa;
                });
            }

            if (tipoFiltro == 'noExistenEnProyectoComparativo') {
                return this.articulosFiltrados = articulos.filter(function (articulo) {
                    return articulo.cantidad_requerida == 0;
                });
            }

            if (tipoFiltro == 'masCarosQueEnProyectoComparativo') {
                return this.articulosFiltrados = articulos.filter(function (articulo) {
                    return articulo.existe_para_comparativa && 
                        articulo.precio_estimado_homologado > articulo.precio_comparativa_homologado;
                })
            }

            if (tipoFiltro == 'masCarosEnProyectoComparativo') {
                return this.articulosFiltrados = articulos.filter(function (articulo) {
                    return articulo.existe_para_comparativa && articulo.cantidad_requerida > 0 &&
                        articulo.precio_comparativa_homologado > articulo.precio_estimado_homologado;
                })
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
        }
    }
});
