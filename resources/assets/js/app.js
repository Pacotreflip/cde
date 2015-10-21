var jquery = require('jquery');
var _ = require('underscore');
var moment = require('moment');
var dropzone = require('dropzone');
var jstree = require('jstree');

window.Vue = require('vue');
require('vue-resource');
Vue.http.headers.common['X-CSRF-TOKEN'] = App.csrfToken;
Vue.use(require('vue-validator'))

window.$ = window.jQuery = jquery;
window._ = _;
window.moment = moment;
window.Dropzone = dropzone;

require('bootstrap/dist/js/bootstrap');
require('jasny-bootstrap/js/rowlink');

if ($('#app').length) {
    new Vue(require('./vue-app'));
}