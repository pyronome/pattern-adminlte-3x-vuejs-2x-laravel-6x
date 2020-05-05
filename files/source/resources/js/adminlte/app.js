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

/* vue-i18n Declaration BOF */
import VueI18n from 'vue-i18n';
Vue.use(VueI18n);
window.I18N = new VueI18n({
    locale: 'en',
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
    color: '#bffaf3',
    failedColor: '#874b4b',
    thickness: '5px',
    transition: {
        speed: '0.2s',
        opacity: '0.6s',
        termination: 300
    },
    autoRevert: true,
    location: 'left',
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

Vue.component('loginform', require('./components/LoginForm.vue').default);
Vue.component('forgotpasswordform', require('./components/ForgotPasswordForm.vue').default);

/* {{@snippet:end_components}} */

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

/* {{@snippet:begin_vue_app}} */

const app = new Vue({
    i18n: I18N,
    el: '#app',
});

/* {{@snippet:end_vue_app}} */

/* {{@snippet:footer}} */