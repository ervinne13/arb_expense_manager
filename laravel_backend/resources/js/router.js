import Vue from 'vue'
import VueRouter from 'vue-router'
import auth from './auth'
import MyExpenses from '@/js/pages/MyExpenses'
import UsersPage from '@/js/pages/UsersPage'
import LoginPage from '@/js/pages/LoginPage.vue'

Vue.use(VueRouter);

const router = new VueRouter({
    mode: 'history',
    routes: [
        { path: '/', component: MyExpenses, meta: { requiresAuth: true } },
        { path: '/login', component: LoginPage, meta: { hideForAuth: true } },
        { path: '/users', component: UsersPage, meta: { requiresAuth: true } },
    ]
});

router.beforeEach(async (to, from, next) => {
    const user = auth.getCurrentUser();
    if (to.matched.some(record => record.meta.requiresAuth)) {
        if (user) {
            console.log(`${user.name} is logged in`)
            next();
            return;
        } else {
            console.log('routing to login')
            router.push('/login');
        }
    } else {
        next();
    }

    if (to.matched.some(record => record.meta.hideForAuth)) {
        if (user) {
            next({ path: '/' });
        } else {
            next();
        }
    } else {
        next();
    }
});

export default router;