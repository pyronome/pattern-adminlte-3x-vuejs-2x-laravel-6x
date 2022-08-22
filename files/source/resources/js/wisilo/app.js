/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

/* {{@snippet:header}} */

require('./bootstrap');

/* {{@snippet:require}} */

/* vue Declaration BOF */
window.Vue = require('vue/dist/vue');
/* vue Declaration EOF */

import _ from 'lodash';
Vue.use(_);

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
    locale: "default",
    fallbackLocale: "en",
    messages: loadLocaleMessages()
});

function loadLocaleMessages() {
    const locales = require.context(
      "../../../locales",
      true,
      /[A-Za-z0-9-_,\s]+\.json$/i
    );
    const messages = {};
    locales.keys().forEach(key => {
      const matched = key.match(/([A-Za-z0-9-_]+)\./i);
      if (matched && matched.length > 1) {
        const locale = matched[1];
        messages[locale] = locales(key);
      }
    });
    return messages;
}
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

import Inputmask from 'inputmask';
Vue.directive('input-mask', {
    bind: function(el) {
        new Inputmask().mask(el);
    },
});

Vue.component('pagination', require('laravel-vue-pagination'));

import VueSweetalert2 from 'vue-sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';
Vue.use(VueSweetalert2);

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

WisiloHelper.initializeApplication();

window.mainVueApplication = new Vue({
    el: '#mainVueApplication',
    i18n: I18N,
    router: window.Router,
    updated() {
        WisiloHelper.doVueApplicationUpdate();
    },
    mounted() {
        WisiloHelper.doVueApplicationMounted();
    },
    watch:{
        $route (to, from){
            WisiloHelper.doRouteChanged(to, from);
        }
    } 
});

if (document.getElementById("mainMenuVueApplication")) {
    window.mainMenuVueApplication = new Vue({
        el: '#mainMenuVueApplication',
        i18n: I18N,
        router: window.Router,
        mounted() {
            WisiloHelper.doVueMenuApplicationMounted();
        },
        updated() {
            WisiloHelper.doVueMenuApplicationUpdate();
        }
    });
}

/* {{@snippet:end_vue_app}} */

/* {{@snippet:footer}} */