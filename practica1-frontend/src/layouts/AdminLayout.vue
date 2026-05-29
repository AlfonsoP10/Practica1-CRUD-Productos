<template>
  <div class="admin-layout">
    <aside class="sidebar">
      <h2>Panel Admin</h2>

      <p v-if="auth.user">
        Usuario: {{ auth.user.name }}
      </p>

      <nav>
        <router-link to="/admin">Dashboard</router-link>
        <router-link to="/admin/productos">Productos</router-link>
        <router-link to="/admin/nuevo">Nuevo Producto</router-link>
      </nav>

      <button @click="cerrarSesion">Cerrar sesión</button>
    </aside>

    <section class="admin-content">
      <router-view />
    </section>
  </div>
</template>

<script setup>
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth'

const router = useRouter()
const auth = useAuthStore()

const cerrarSesion = () => {
  auth.logout()
  router.push('/login')
}
</script>

<style scoped>
.admin-layout {
  display: flex;
  min-height: 70vh;
}

.sidebar {
  width: 240px;
  background: #222;
  color: white;
  padding: 20px;
}

.sidebar nav {
  display: flex;
  flex-direction: column;
  gap: 12px;
  margin: 20px 0;
}

.sidebar a {
  color: white;
  text-decoration: none;
}

.sidebar a.router-link-active {
  color: #42b883;
}

.admin-content {
  flex: 1;
  padding: 25px;
  background: #f4f6f8;
}
</style>