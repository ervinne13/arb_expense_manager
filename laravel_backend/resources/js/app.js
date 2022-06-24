import "./bootstrap"
import Vue from "vue"
import BootstrapVue from 'bootstrap-vue'
import 'bootstrap-vue/dist/bootstrap-vue.css'
import VueDashboard from 'vue-dashboard-vd';
import 'bulma/css/bulma.min.css'

import axios from 'axios'
import VueAxios from 'vue-axios'
import VeeValidate from 'vee-validate'

import router from '@/js/router'
// import App from '@/js/layouts/DefaultLayout'
import App from '@/js/layouts/DashboardLayout'
import { registerDashboardComponents } from '@/js/components/Dashboard/register'
import { registerChartComponents } from "@/js/charts/register";
import { registerComponents } from "./components/register";

Vue.use(VueDashboard);
Vue.use(BootstrapVue)
Vue.use(VueAxios, axios)
Vue.use(VeeValidate, {
    inject: true,
    // Important to name this something other than 'fields'
    fieldsBagName: 'veeFields',
    // This is not required but avoids possible naming conflicts
    errorBagName: 'veeErrors'
})

Vue.component(
    'passport-clients',
    require('./components/passport/Clients.vue').default
);

Vue.component(
    'passport-authorized-clients',
    require('./components/passport/AuthorizedClients.vue').default
);

Vue.component(
    'passport-personal-access-tokens',
    require('./components/passport/PersonalAccessTokens.vue').default
);

registerComponents()
registerChartComponents()
registerDashboardComponents()

window.onload = function () {
    const app = new Vue({
        el: '#app',
        router,
        render: h => h(App)
    });
}

export default app;