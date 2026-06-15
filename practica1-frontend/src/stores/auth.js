import { defineStore } from 'pinia'
import axios from '@/plugins/axios'

export const useAuthStore = defineStore('auth', {
  state: () => ({
    token: localStorage.getItem('token') || null,
    user: JSON.parse(localStorage.getItem('user') || 'null'),
    permisos: JSON.parse(localStorage.getItem('permisos') || 'null') || {
      crear: false,
      editar: false,
      eliminar: false,
    },
  }),

  getters: {
    isAuthenticated: (state) => !!state.token,
    isAdmin: (state) => state.user?.rol === 'admin',
  },

  actions: {
    async login(email, password) {
      const { data } = await axios.post('/login', {
        email,
        password,
      })

      this.token = data.token
      this.user = data.user

      localStorage.setItem('token', this.token)
      localStorage.setItem('user', JSON.stringify(this.user))

      await this.fetchUser()

      return true
    },

    async fetchUser() {
      if (!this.token) return

      try {
        const { data } = await axios.get('/me')

        this.user = {
          id: data.id,
          name: data.name,
          email: data.email,
          rol: data.rol,
        }

        this.permisos = data.permisos || {
          crear: false,
          editar: false,
          eliminar: false,
        }

        localStorage.setItem('user', JSON.stringify(this.user))
        localStorage.setItem('permisos', JSON.stringify(this.permisos))
      } catch (error) {
        this.logout()
      }
    },

    async logout() {
      try {
        if (this.token) {
          await axios.post('/logout')
        }
      } catch (error) {
        console.log('Sesión cerrada o token inválido')
      }

      this.token = null
      this.user = null
      this.permisos = {
        crear: false,
        editar: false,
        eliminar: false,
      }

      localStorage.removeItem('token')
      localStorage.removeItem('user')
      localStorage.removeItem('permisos')
    },
  },
})