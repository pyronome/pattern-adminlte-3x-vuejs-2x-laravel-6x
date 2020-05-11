/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

/* {{@snippet:header}} */

require('./bootstrap');

/* {{@snippet:require}} */

/* vue Declaration BOF */
window.Vue = require('vue');
/* vue Declaration EOF */

/* vue-router Declaration BOF */
import VueRouter from 'vue-router';
import { Routes } from './routes';
Vue.use(VueRouter);
window.Router = new VueRouter({
    base: "",
    mode: 'history',
    routes: Routes
});
/* vue-router Declaration EOF */

/* vue-i18n Declaration BOF */
import VueI18n from 'vue-i18n';
Vue.use(VueI18n);
window.I18N = new VueI18n({
    locale: "en",
    messages: {}
});
/* vue-i18n Declaration EOF */

/* vform Declaration BOF */
import { Form, HasError, AlertError } from 'vform';
window.Form = Form;
Vue.component(HasError.name, HasError);
Vue.component(AlertError.name, AlertError);
/* vform Declaration EOF */

/* vue-progressbar Declaration BOF */
import VueProgressBar from 'vue-progressbar';
Vue.use(VueProgressBar, {
    color: "rgb(0,123,255,0.5)",
    failedColor: "#e3342f",
    thickness: '3px',
    transition: {
        speed: '0.2s',
        opacity: '0.6s',
        termination: 300
    },
    autoRevert: true,
    location: 'top',
    inverse: false
});
/* vue-progressbar Declaration EOF */

/* {{@snippet:global_objects}} */

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

// Vue.component('example-component', require('./components/ExampleComponent.vue').default);

/* {{@snippet:begin_components}} */

require('./components');

/* {{@snippet:end_components}} */

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

/* {{@snippet:begin_vue_app}} */

AdminLTEHelper.initializeApplication();

window.mainVueApplication = new Vue({
    el: '#mainVueApplication',
    i18n: I18N,
    router: window.Router,
    updated() {
        AdminLTEHelper.doVueApplicationUpdate();
    },
    mounted() {
        AdminLTEHelper.doVueApplicationMounted();
    }
});

if (document.getElementById("mainMenuVueApplication")) {
    window.mainMenuVueApplication = new Vue({
        el: '#mainMenuVueApplication',
        i18n: I18N,
        router: window.Router,
        mounted() {
            AdminLTEHelper.doVueMenuApplicationMounted();
        },
        updated() {
            AdminLTEHelper.doVueMenuApplicationUpdate();
        }
    });
}

/* {{@snippet:end_vue_app}} */

/* {{@snippet:footer}} */