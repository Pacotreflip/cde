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
        sinDecimales: function (valor) {
            return parseInt(valor);
        },

        filtro: function (articulos, tipoFiltro) {
            if (tipoFiltro == 'todos') {
                return this.articulosFiltrados = articulos;
            }

            if (tipoFiltro == 'existenEnEsteProyecto') {
                return this.articulosFiltrados = this.articulosQueExistenEnEsteProyecto(articulos);
            }

            if (tipoFiltro == 'soloExistenEnEsteProyecto') {
                return this.articulosFiltrados = this.articulosQueSoloExistenEnEsteProyecto(articulos);
            }

            if (tipoFiltro == 'soloExistenEnProyectoComparativo') {
                return this.articulosFiltrados = this.articulosQueSoloExistenEnProyectoComparativo(articulos);
            }

            if (tipoFiltro == 'masCarosEnEsteProyecto') {
                return this.articulosFiltrados = this.articulosMasCarosEnEsteProyecto(articulos);
            }

            if (tipoFiltro == 'masCarosEnProyectoComparativo') {
                return this.articulosFiltrados = this.articulosMasCarosEnProyectoComparativo(articulos);
            }

            if (tipoFiltro == 'sinPrecioEnEsteProyecto') {
                return this.articulosFiltrados = this.articulosSinPrecioEnEsteProyecto(articulos);
            }

            if (tipoFiltro == 'sinPrecioEnProyectoComparativo') {
                return this.articulosFiltrados = this.articulosSinPrecioEnProyectoComparativo(articulos);
            }

            if (tipoFiltro == 'cotizadosEnEsteProyecto') {
                return this.articulosFiltrados = this.articulosCotizadosEnEsteProyecto(articulos);
            }

            if (tipoFiltro == 'soloCotizadosEnEsteProyecto') {
                return this.articulosFiltrados = this.articulosSoloCotizadosEnEsteProyecto(articulos);
            }

            if (tipoFiltro == 'existenEnProyectoComparativo') {
                return this.articulosFiltrados = this.articulosQueExistenEnProyectoComparativo(articulos);
            }

            if (tipoFiltro == 'sinNombre') {
                return this.articulosFiltrados = this.sinNombre(articulos);
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

        importeTotalDiferencia: function () {
            return Math.abs(this.articulosFiltrados.reduce(function (prev, cur) {
                return prev + (cur.importe_estimado_homologado - cur.importe_comparativa_homologado);
            }, 0));
        },

        cuentaDeArticulos: function () {
            return this.articulosFiltrados.length;
        },

        cuentaArticulosEsteProyecto: function () {
            return this.articulosQueExistenEnEsteProyecto(this.articulosFiltrados).length;
        },

        sumaCantidadRequeridaEsteProyecto: function () {
            return this.articulosQueExistenEnEsteProyecto(this.articulosFiltrados).reduce(function (prev, cur) {
                return prev + cur.cantidad_requerida;
            }, 0);
        },

        cuentaArticulosProyectoComparativo: function () {
            return this.articulosQueExistenEnProyectoComparativo(this.articulosFiltrados).length;
        },

        sumaCantidadProyectoComparativo: function () {
            return this.articulosQueExistenEnProyectoComparativo(this.articulosFiltrados).reduce(function (prev, cur) {
                return prev + cur.cantidad_comparativa;
            }, 0);
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

        articulosQueExistenEnEsteProyecto: function (articulos) {
            return articulos.filter(function (articulo) {
                return this.articuloExisteEnEsteProyecto(articulo);
            }.bind(this));
        },

        articulosQueSoloExistenEnEsteProyecto: function (articulos) {
            return articulos.filter(function (articulo) {
                return this.articuloSoloExisteEnEsteProyecto(articulo);
            }.bind(this));
        },

        articulosQueExistenEnProyectoComparativo: function (articulos) {
            return articulos.filter(function (articulo) {
                return articulo.cantidad_comparativa > 0;
            }.bind(this));
        },

        sinNombre: function (articulos) {
            var filtro1 = this.articulosSoloCotizadosEnEsteProyecto(articulos);
            var filtro2 = this.articulosQueSoloExistenEnProyectoComparativo(articulos);

            return this.articulosFiltrados = filtro1.concat(filtro2);
        },

        articulosQueSoloExistenEnProyectoComparativo: function (articulos) {
            return articulos.filter(function (articulo) {
                return this.articuloSoloExisteEnProyectoComparativo(articulo);
            }.bind(this));
        },

        articulosMasCarosEnEsteProyecto: function (articulos) {
            return articulos.filter(function (articulo) {
                return this.articuloExisteEnAmbosProyectos(articulo) &&
                    articulo.precio_estimado_homologado > articulo.precio_comparativa_homologado;
            }.bind(this));
        },

        articulosMasCarosEnProyectoComparativo: function (articulos) {
            return articulos.filter(function (articulo) {
                return this.articuloExisteEnAmbosProyectos(articulo) &&
                    articulo.precio_comparativa_homologado > articulo.precio_estimado_homologado;
            }.bind(this));
        },

        articulosSinPrecioEnEsteProyecto: function (articulos) {
            return articulos.filter(function (articulo) {
                return ! articulo.precio_estimado_homologado || articulo.precio_estimado_homologado == 0;
            });
        },

        articulosSinPrecioEnProyectoComparativo: function (articulos) {
            return articulos.filter(function (articulo) {
                return ! articulo.precio_comparativa_homologado || articulo.precio_comparativa_homologado == 0;
            });
        },

        articulosCotizadosEnEsteProyecto: function (articulos) {
            return articulos.filter(function (articulo) {
                return articulo.precio_estimado_homologado > 0 && articulo.existe_para_comparativa;
            });
        },

        articulosSoloCotizadosEnEsteProyecto: function (articulos) {
            return articulos.filter(function (articulo) {
                return articulo.precio_estimado_homologado > 0;
            });
        },


        articuloExisteEnAmbosProyectos: function (articulo) {
            return articulo.existe_para_comparativa && articulo.cantidad_requerida > 0;
        },

        articuloExisteEnEsteProyecto: function (articulo) {
            return articulo.cantidad_requerida > 0;
        },

        articuloSoloExisteEnEsteProyecto: function (articulo) {
            return articulo.cantidad_requerida > 0 && (articulo.cantidad_comparativa == 0 || ! articulo.cantidad_comparativa);
        },

        articuloSoloExisteEnProyectoComparativo: function (articulo) {
            return articulo.cantidad_requerida == 0 && articulo.cantidad_comparativa > 0;
        },

        costoAcumuladoCorrienteEsteProyecto: function (ix) {
            return this.articulosFiltrados.reduce(function (prev, cur, curIndex) {
                if (curIndex <= ix) {
                    return prev + cur.importe_estimado_homologado;
                }

                return prev;
            }, 0);
        },

        costoAcumuladoCorrienteProyectoComparativo: function (ix) {
            return this.articulosFiltrados.reduce(function (prev, cur, curIndex) {
                if (curIndex <= ix) {
                    return prev + cur.importe_comparativa_homologado;
                }

                return prev;
            }, 0);
        }
    }
});
