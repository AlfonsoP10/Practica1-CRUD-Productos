<script setup>
import CartIcon from '@/components/CartIcon.vue'
import { useAuthStore } from '@/stores/auth'

const auth = useAuthStore()

const cerrarSesion = async () => {
  await auth.logout()
}
</script>

<template>
  <div>
    <nav class="navbar">
      <router-link to="/">Inicio</router-link>
      <router-link to="/catalogo">Catálogo</router-link>

     <router-link
      v-if="auth.isAdmin"
      to="/admin/productos"
    >
      Admin
    </router-link>

      <router-link
        v-if="!auth.isAuthenticated"
        to="/login"
      >
        Login
      </router-link>

      <span
        v-else
        class="usuario"
      >
        {{ auth.user?.name }} ({{ auth.user?.rol }})
      </span>

      <button
        v-if="auth.isAuthenticated"
        class="btn-logout"
        @click="cerrarSesion"
      >
        Salir
      </button>

      <CartIcon />
    </nav>

    <main class="contenido">
      <router-view />
    </main>
  </div>
</template>

<style scoped>
.navbar {
  background: #222;
  padding: 15px;
  display: flex;
  gap: 15px;
  align-items: center;
}

.navbar a {
  color: white;
  text-decoration: none;
  font-weight: bold;
}

.navbar a.router-link-active {
  color: #42b883;
}

.usuario {
  color: #facc15;
  font-weight: bold;
}

.btn-logout {
  background: #ef4444;
  color: white;
  border: none;
  padding: 7px 10px;
  border-radius: 6px;
  cursor: pointer;
}

.contenido {
  padding: 25px;
}
</style>