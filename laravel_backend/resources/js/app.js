import "./bootstrap"
import Vue from "vue"
import BootstrapVue from 'bootstrap-vue'
import 'bootstrap-vue/dist/bootstrap-vue.css'
import router from '@/js/router'

import App from '@/js/pages/App'

Vue.use(BootstrapVue)

window.onload = function () {
    const app = new Vue({
        el: '#app',
        router,
        render: h => h(App)
    });
}

export default app;