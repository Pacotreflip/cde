/*
 * Load the Spark components.
 */
require('./components');

/**
 * Export the Spark application.
 */
module.exports = {
    el: '#app',

    /*
     * Bootstrap the application. Load the initial data.
     */
    ready: function () {
        this.whenReady();
    },


    events: {
        /**
         * Receive an updated team list from a child component.
         */
        teamsUpdated: function (teams) {
            this.$broadcast('teamsRetrieved', teams);
        }
    },


    methods: {
        /**
         * This method would be overridden by developer.
         */
        whenReady: function () {
            //
        },

        /**
         * Retrieve the user from the API and broadcast it to children.
         */
        getUser: function () {
            this.$http.get('/spark/api/users/me')
                .success(function(user) {
                    this.$broadcast('userRetrieved', user);
                });
        }
    }
};
