<template>
  <div>
    <button @click="volver">
      Volver al catálogo
    </button>

    <p v-if="error" style="color: red">
      {{ error }}
    </p>

    <div v-if="producto" class="detalle">
      <h1>{{ producto.nombre }}</h1>
      <p>{{ producto.descripcion }}</p>
      <p><strong>Precio:</strong> ${{ producto.precio }}</p>
      <p><strong>Stock:</strong> {{ producto.stock }}</p>
    </div>

    <p v-else-if="!error">
      Cargando producto...
    </p>
  </div>
</template>

<script setup>
import { onMounted, ref } from 'vue'
import { useRouter } from 'vue-router'
import axios from 'axios'

const props = defineProps({
  id: {
    type: String,
    required: true,
  },
})

const router = useRouter()
const producto = ref(null)
const error = ref('')

const cargarProducto = async () => {
  try {
    const respuesta = await axios.get(`http://localhost:8000/api/productos/${props.id}`)
    producto.value = respuesta.data
  } catch (e) {
    error.value = 'No se pudo cargar el producto. Verifica que Laravel esté encendido.'
  }
}

const volver = () => {
  router.back()
}

onMounted(() => {
  cargarProducto()
})
</script>

<style scoped>
.detalle {
  background: white;
  padding: 20px;
  border-radius: 8px;
  margin-top: 20px;
}
</style>