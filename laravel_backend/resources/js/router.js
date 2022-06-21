import Vue from 'vue'
import VueRouter from 'vue-router'
import UsersPage from '@/js/pages/UsersPage.vue'

Vue.use(VueRouter);

const router = new VueRouter({
    mode: 'history',
    routes: [
        {
            path: '/users',
            name: 'users',
            component: UsersPage
        }
    ]
});

export default router;