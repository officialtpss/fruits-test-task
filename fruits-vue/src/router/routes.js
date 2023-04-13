import { auth, guest } from '../middleware/auth'

const routes = [
  {
    path: '/',
    name: 'home',
    component: () => import('../views/HomeView.vue')
  },
  {
    path: '/fruits',
    name: 'fruits',
    component: () => import('../views/FruitsView.vue'),
    meta: {
      middleware: [auth]
    }
  },
  {
    path: '/favorite-fruits',
    name: 'favorite-fruits',
    component: () => import('../views/FavFruitsView.vue'),
    meta: {
      middleware: [auth]
    }
  },
  {
    path: '/login',
    name: 'login',
    component: () => import('../views/LoginView.vue'),
    meta: {
      middleware: [guest]
    }
  }
]

export default routes
