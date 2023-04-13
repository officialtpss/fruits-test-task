import { defineStore } from 'pinia'
import { useAlertStore } from './alert.store'
import axiosInstance from '@/services/api'

export const useAuthStore = defineStore({
  id: 'auth',
  state: () => ({
    user: null,
    token: localStorage.getItem('token') ?? null
  }),

  actions: {
    async login(username, password) {
      const alertStore = useAlertStore()

      try {
        const user = await axiosInstance.post('login_check', { username, password })

        this.$state.user = {
          username
        }

        this.$state.token = user.data.token

        localStorage.setItem('token', user.data.token)

        // redirect
        this.router.push(this.returnUrl || '/')

        alertStore.success('You are logged in successfully!', 'login')

        return user.data
      } catch (error) {
        alertStore.error(
          error?.response?.data?.message ??
            error?.message ??
            'Something went wrong. Please try again!'
        )
      }
    },
    logout() {
      const alertStore = useAlertStore()

      localStorage.removeItem('token')

      this.user = null
      this.$state.token = null

      this.router.push({ name: 'login' })

      alertStore.success('You are logout successfully!')
    }
  },
  getters: {
    isLoggedIn: (state) => !!state.token
  }
})
