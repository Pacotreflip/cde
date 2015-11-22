Vue.component('screen-comparativa', {

    data: function () {
        return {
            tipo_cambio: 0,
            articulos: [],
            sortKey: '',
            reverse: 1
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

    computed: {
        importeTotalEstimado: function () {
            return this.articulos.reduce(function (prev, cur) {
                return prev + cur.importe_estimado_homologado;
            }, 0);
        },

        importeTotalComparativa: function () {
            return this.articulos.reduce(function (prev, cur) {
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
