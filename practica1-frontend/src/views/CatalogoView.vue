<template>
  <div>
    <h1>Catálogo de Productos</h1>

    <input
      v-model="busqueda"
      type="text"
      placeholder="Buscar producto..."
      class="buscador"
    >

    <div class="grid">
      <div
        v-for="producto in productosFiltrados"
        :key="producto.id"
        class="card"
      >
        <h2>{{ producto.nombre }}</h2>
        <p>{{ producto.descripcion }}</p>
        <p><strong>Precio:</strong> ${{ producto.precio }}</p>
        <p><strong>Stock:</strong> {{ producto.stock }}</p>

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
const busqueda = ref('')

const cargarProductos = async () => {
  const respuesta = await getProductos()
  productos.value = respuesta.data
}

const productosFiltrados = computed(() =>
  productos.value.filter((p) =>
    p.nombre.toLowerCase().includes(busqueda.value.toLowerCase())
  )
)

onMounted(() => {
  cargarProductos()
})
</script>

<style scoped>
.buscador {
  width: 100%;
  max-width: 400px;
  padding: 10px;
  margin-bottom: 20px;
}

.grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
  gap: 20px;
}

.card {
  background: white;
  border: 1px solid #ddd;
  padding: 15px;
  border-radius: 8px;
}
</style>