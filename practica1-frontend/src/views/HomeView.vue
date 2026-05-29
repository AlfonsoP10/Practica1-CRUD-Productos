<template>
  <div>
    <section class="hero">
      <h1>Bienvenido a la tienda online</h1>
      <p>Consulta nuestro catálogo de productos disponibles.</p>

      <router-link to="/catalogo">
        Ver catálogo
      </router-link>
    </section>

    <h2>Últimos productos</h2>

    <div class="grid">
      <div
        v-for="producto in ultimosProductos"
        :key="producto.id"
        class="card"
      >
        <h3>{{ producto.nombre }}</h3>
        <p>${{ producto.precio }}</p>

        <router-link :to="`/catalogo/${producto.id}`">
          Ver detalle
        </router-link>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed, onMounted, ref } from 'vue'
import { getProductos } from '@/services/productoService'

const productos = ref([])

const cargarProductos = async () => {
  const respuesta = await getProductos()
  productos.value = respuesta.data
}

const ultimosProductos = computed(() => productos.value.slice(-3))

onMounted(() => {
  cargarProductos()
})
</script>

<style scoped>
.hero {
  background: #222;
  color: white;
  padding: 30px;
  border-radius: 10px;
  margin-bottom: 25px;
}

.hero a {
  color: #42b883;
  font-weight: bold;
}

.grid {
  display: flex;
  gap: 15px;
  flex-wrap: wrap;
}

.card {
  background: white;
  padding: 15px;
  border-radius: 8px;
  width: 220px;
  border: 1px solid #ddd;
}
</style>