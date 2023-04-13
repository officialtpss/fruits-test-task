export function auth({ next, store }) {
  if (!store.isLoggedIn) {
    next({ name: 'login' })
  }

  return next()
}

export function guest({ next, store }) {
  if (store.isLoggedIn) {
    next({ name: 'home' })

    return
  }

  return next()
}
