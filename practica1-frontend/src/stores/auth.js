import { defineStore } from 'pinia'

export const useAuthStore = defineStore('auth', {
  state: () => ({
    token: localStorage.getItem('token'),
    user: JSON.parse(localStorage.getItem('user')) || null,
  }),

  getters: {
    isAuthenticated: (state) => !!state.token,
  },

  actions: {
    async login(email, password) {
      // Login simulado para la práctica
      if (email === 'admin@test.com' && password === '123456') {
        this.token = 'token-prueba'

        this.user = {
          name: 'Administrador',
          email: email,
        }

        localStorage.setItem('token', this.token)
        localStorage.setItem('user', JSON.stringify(this.user))

        return true
      }

      throw new Error('Credenciales incorrectas')
    },

    async fetchUser() {
      const userGuardado = localStorage.getItem('user')

      if (userGuardado) {
        this.user = JSON.parse(userGuardado)
      }
    },

    logout() {
      this.token = null
      this.user = null

      localStorage.removeItem('token')
      localStorage.removeItem('user')
    },
  },
})