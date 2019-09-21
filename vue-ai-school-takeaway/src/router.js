import Vue from 'vue'
import Router from 'vue-router'

Vue.use(Router)

export default new Router({
  mode: 'history',
  base: process.env.BASE_URL,
  routes: [
    {
      path: '/',
      // name: 'index',
      component: () => import('./views/index.vue'),
      children: [
        {
          path: '',
          redirect: '/home'
        },
        {
          path: '/home',
          name: 'home',
          component: () => import('./views/Home.vue')
        },
        {
          path: '/order',
          name: 'order',
          component: () => import('./views/Order.vue')
        },
        {
          path: '/me',
          name: 'me',
          component: () => import('./views/Me.vue')
        }
      ]
    },
    {
      path: '/shop',
      name: 'shop',
      redirect: '/goods',
      component: () => import('./views/Shops/Shop.vue'),
      children: [
        {
          path: '/goods',
          name: 'goods',
          component: () => import('./views/Shops/Goods.vue')
        },
        {
          path: '/comments',
          name: 'comments',
          component: () => import('./views/Shops/Comments.vue')
        }
      ]
    },
    {
      path: '/login',
      name: 'login',
      component: () => import('./views/Login.vue')
    },
    {
      path: '/myAddress',
      name: 'myAddress',
      component: () => import('./views/Orders/MyAddress.vue')
    },
    {
      path: '/addAddress',
      name: 'addAddress',
      component: () => import('./views/Orders/AddAddress.vue')
    },
    {
      path: '/settlement',
      name: 'settlement',
      component: () => import('./views/Orders/Settlement.vue')
    },
    {
      path: '/remark',
      name: 'remark',
      component: () => import('./views/Orders/Remark.vue')
    },
    {
      path: '/pay',
      name: 'pay',
      component: () => import('./views/Orders/Pay.vue')
    },
    {
      path: '/orderInfo',
      name: 'orderInfo',
      component: () => import('./views/Orders/OrderInfo.vue')
    }
  ]
})
