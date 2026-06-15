<template>
  <div class="admin-layout">
    <aside class="sidebar">
      <h2>Panel Admin</h2>

      <p v-if="auth.user">
        Usuario: {{ auth.user.name }}
      </p>

      <nav>
        <router-link to="/admin/productos">
          Productos
        </router-link>

        <router-link to="/admin/nuevo">
          Nuevo Producto
        </router-link>
      </nav>
    </aside>

    <section class="admin-content">
      <div
        style="
          background:red;
          color:white;
          padding:10px;
          margin-bottom:15px;
          border-radius:6px;
        "
      >
        PRUEBA ADMIN LAYOUT
      </div>

      <AdminNotificaciones />

      <router-view />
    </section>
  </div>
</template>

<script setup>
import { useAuthStore } from '@/stores/auth'
import AdminNotificaciones from '@/components/AdminNotificaciones.vue'

const auth = useAuthStore()
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