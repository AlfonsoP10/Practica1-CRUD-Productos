<template>
  <aside class="filtros-panel">
    <h3>Filtros</h3>

    <div>
      <label>Búsqueda:</label>
      <input
        v-model="busquedaLocal"
        type="text"
        placeholder="Buscar producto..."
      >
    </div>

    <div>
      <label>Categoría:</label>
      <select v-model="filtros.categoria_id">
        <option value="">Todas</option>
        <option
          v-for="cat in categorias"
          :key="cat.id"
          :value="cat.id"
        >
          {{ cat.nombre }}
        </option>
      </select>
    </div>

    <div>
      <label>Precio mínimo:</label>
      <input v-model="filtros.precio_min" type="number" min="0">
    </div>

    <div>
      <label>Precio máximo:</label>
      <input v-model="filtros.precio_max" type="number" min="0">
    </div>

    <div>
      <label>Ordenar:</label>
      <select v-model="filtros.orden">
        <option value="nombre-asc">Nombre A-Z</option>
        <option value="precio-asc">Precio menor</option>
        <option value="precio-desc">Precio mayor</option>
      </select>
    </div>

    <button type="button" @click="limpiarFiltros">
      Limpiar filtros
    </button>
  </aside>
</template>

<script setup>
import axios from 'axios'
import { onMounted, ref, watch } from 'vue'

const props = defineProps({
  filtros: {
    type: Object,
    required: true,
  },
})

const categorias = ref([])
const busquedaLocal = ref(props.filtros.busqueda)
let temporizador = null

watch(busquedaLocal, (valor) => {
  clearTimeout(temporizador)

  temporizador = setTimeout(() => {
    props.filtros.busqueda = valor
    props.filtros.pagina = 1
  }, 300)
})

watch(
  () => props.filtros.categoria_id,
  () => {
    props.filtros.pagina = 1
  }
)

watch(
  () => props.filtros.precio_min,
  () => {
    props.filtros.pagina = 1
  }
)

watch(
  () => props.filtros.precio_max,
  () => {
    props.filtros.pagina = 1
  }
)

watch(
  () => props.filtros.orden,
  () => {
    props.filtros.pagina = 1
  }
)

const cargarCategorias = async () => {
  const respuesta = await axios.get('http://localhost:8000/api/v1/categorias')
  categorias.value = respuesta.data.data
}

const limpiarFiltros = () => {
  props.filtros.busqueda = ''
  props.filtros.categoria_id = ''
  props.filtros.precio_min = ''
  props.filtros.precio_max = ''
  props.filtros.orden = 'nombre-asc'
  props.filtros.pagina = 1
  busquedaLocal.value = ''
}

onMounted(() => {
  cargarCategorias()
})
</script>

<style scoped>
.filtros-panel {
  background: #f9fafb;
  border: 1px solid #e5e7eb;
  padding: 16px;
  border-radius: 12px;
  margin-bottom: 24px;
}

h3 {
  margin-top: 0;
}

div {
  margin-bottom: 12px;
}

label {
  display: block;
  font-weight: 600;
  margin-bottom: 5px;
}

input,
select {
  width: 100%;
  padding: 8px;
  box-sizing: border-box;
}

button {
  padding: 8px 12px;
  cursor: pointer;
}
</style>