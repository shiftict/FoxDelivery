/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue').default;

let Lang = require('vuejs-localization');
//Notice that you need to specify the lang folder, in this case './lang'
//npm install vuejs-localization
Lang.requireAll(require.context('./lang', true, /\.js$/));
Vue.use(Lang);

// setup v-validate component
import {ValidationProvider, ValidationObserver, localize} from 'vee-validate/dist/vee-validate.full';
import ar from 'vee-validate/dist/locale/ar.json';

localize('ar', ar)
/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('data-table', require('./components/general/datatables/DataTable.vue').default);
Vue.component('method-shipping-display', require('./components/methodShipping/Display.vue').default);
/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
});
