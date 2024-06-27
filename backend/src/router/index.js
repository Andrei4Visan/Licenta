import {createRouter, createWebHistory} from "vue-router";
import AppLayout from "../components/AppLayout.vue";
import Dashboard from "../views/Dashboard.vue";
import Login from "../views/Login.vue";
import RequestPassword from "../views/RequestPassword.vue";
import ResetPassword from "../views/ResetPassword.vue";
import Registration from "../views/Registration.vue";
import store from "../store/index.js";
import Products from "../views/Products/Products.vue";
import Users from "../views/Users/Users.vue";
import Orders from "../views/Orders/Orders.vue";
import OrderView from "../views/Orders/OrderView.vue";
import Customers from "../views/Customers/Customers.vue";
// import dashboard from "../views/Dashboard.vue";




const routes = [
    {
        path: '/app',
        name: 'app',
        component: AppLayout,
        meta:{
            requireAuth: true
        },
        children:[
            {
                path: 'dashboard',
                name: 'app.dashboard',
                component: Dashboard
            },
            {
                path: 'products',
                name: 'app.products',
                component: Products
            },
            {
                path: 'users',
                name: 'app.users',
                component: Users
            },
            {
                path: 'customers',
                name: 'app.customers',
                component: Customers
            },

            {
                path: 'orders',
                name: 'app.orders',
                component: Orders
            },
            {
                path: 'orders/:id',
                name: 'app.orders.view',
                component: OrderView
            }
        ]
    },
    {
        path: '/login',
        name: 'login',
        meta:{
            requireGuest: true
        },
        component: Login
    },
    {
        path: '/request-password',
        name: 'requestPassword',
        meta:{
            requireGuest: true
        },
        component: RequestPassword
    },
    {
        path: '/reset-password/:token',
        name: 'resetPassword',
        meta:{
            requireGuest: true
        },
        component: ResetPassword
    },
    {
        path: '/:pathMatch(.*)',
        name: 'notfound',
        component: Registration
    },
    {
        path: '/test',
        name: 'test',
        component: { template: '<div>Test route working!</div>' }
    }
]

const router = createRouter(
    {
        history: createWebHistory(),
        routes
    })

router.beforeEach((to, from, next) => {
    if (to.meta.requireAuth && !store.state.user.token) {
        next({ name: "login" });
    } else if(to.meta.requireGuest && store.state.user.token) {
        next({name: 'app.dashboard'});
    }else{
        next();
    }
});

export default router;



