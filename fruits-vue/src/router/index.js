import { createRouter, createWebHistory } from 'vue-router'
import routes from './routes.js'
import { useAuthStore } from '../stores/auth.store.js'
import middlewarePipeline from '../middleware'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes
})

router.beforeEach((to, from, next) => {
  if (!to.meta.middleware) {
    return next()
  }

  const middleware = to.meta.middleware

  const store = useAuthStore()

  const context = {
    to,
    from,
    next,
    store
  }

  return middleware[0]({
    ...context,
    next: middlewarePipeline(context, middleware, 1)
  })
})

export default router
