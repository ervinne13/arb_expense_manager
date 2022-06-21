import Vue from 'vue'
import VueRouter from 'vue-router'
import auth from './auth'
import UsersPage from '@/js/pages/UsersPage.vue'
import LoginPage from '@/js/pages/LoginPage.vue'

Vue.use(VueRouter);

const router = new VueRouter({
    mode: 'history',
    routes: [
        { path: '/login', name: 'login', component: LoginPage },
        { path: '/users', name: 'users', component: UsersPage, requiresAuth: true },
    ]
});

router.beforeEach((to, from, next) => {
    console.log('before each')
    if (to.matched.some(record => record.meta.requiresAuth)) {
        user = auth.check()
        if (user) {
            this.user = user
            next();
            return;
        } else {
            router.push('/login');
        }
        // if (Auth.check()) {
        //     next();
        //     return;
        // } else {
        //     router.push('/login');
        // }
    } else {
        next();
    }
});

export default router;