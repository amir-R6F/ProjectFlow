

require('./bootstrap');

window.Vue = require('vue').default;

import { BootstrapVue, BootstrapVueIcons } from 'bootstrap-vue'

import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-vue/dist/bootstrap-vue.css'
import "vue-select/dist/vue-select.css";
import {ValidationProvider, ValidationObserver} from 'vee-validate';
import vSelect from 'vue-select'
import  helper from './helper';

Vue.use(BootstrapVue)
Vue.use(BootstrapVueIcons)
Vue.component('ValidationProvider', ValidationProvider);
Vue.component('ValidationObserver', ValidationObserver);
Vue.component('v-select', vSelect)

import "./VeeValidate";

Vue.component('alert-component', require('./components/tools/AlertComponent').default);
Vue.component('document-manager', require('./components/tools/DocumentManager').default);
Vue.component('progress-bar', require('./components/tools/ProgressBar').default);
Vue.component('time-countdown', require('./components/tools/TimeCountdown').default);
Vue.component('context-menu', require('./components/tools/ContextMenu').default);

Vue.component('project-component', require('./components/ProjectComponent').default);
Vue.component('show-project', require('./components/ShowProject').default);
Vue.component('task-project', require('./components/TaskProject').default);

Vue.component('insert-project', require('./components/InsertProject').default);
Vue.component('insert-task', require('./components/InsertTask.vue').default);


Vue.component('task-manager', require('./components/TaskManager').default);

Vue.component('slider-component', require('./components/tools/SliderComponent').default);




Vue.prototype.$helper = helper;

const app = new Vue({
    el: '#app',
});
