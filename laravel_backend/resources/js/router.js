import Vue from 'vue'
import VueRouter from 'vue-router'
import auth from './auth'
import LoginPage from '@/js/pages/LoginPage.vue'
import MyExpenses from '@/js/pages/MyExpenses'
import UsersPage from '@/js/pages/UsersPage'
import RolesPage from '@/js/pages/RolesPage'
import ExpenseCategoriesPage from '@/js/pages/ExpenseCategoriesPage'
import ExpensesPage from '@/js/pages/ExpensesPage'

Vue.use(VueRouter);

const router = new VueRouter({
    mode: 'history',
    routes: [
        { path: '/', component: MyExpenses, meta: { requiresAuth: true } },
        { path: '/login', component: LoginPage, meta: { hideForAuth: true } },
        { path: '/users', component: UsersPage, meta: { requiresAuth: true } },
        { path: '/roles', component: RolesPage, meta: { requiresAuth: true } },
        { path: '/expense-categories', component: ExpenseCategoriesPage, meta: { requiresAuth: true } },
        { path: '/expenses', component: ExpensesPage, meta: { requiresAuth: true } },
    ]
});

router.beforeEach(async (to, from, next) => {
    const user = auth.getCurrentUser();
    if (to.matched.some(record => record.meta.requiresAuth)) {
        if (user) {
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