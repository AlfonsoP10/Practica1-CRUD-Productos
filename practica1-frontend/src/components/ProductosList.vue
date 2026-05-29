<template>
  <div>
    <h1>Productos</h1>

   <!--
<ProductoForm
  :productoEditar="productoSeleccionado"
  @productoGuardado="productoGuardado"
  @cancelarEdicion="cancelarEdicion"
/>
-->

    <p v-if="mensaje" style="color: green">
      {{ mensaje }}
    </p>

    <p v-if="error" style="color: red">
      {{ error }}
    </p>

    <table border="1" cellpadding="8">
      <thead>
        <tr>
          <th>ID</th>
          <th>Nombre</th>
          <th>Descripción</th>
          <th>Precio</th>
          <th>Stock</th>
          <th>Acciones</th>
        </tr>
      </thead>

      <tbody>
        <tr v-for="producto in productos" :key="producto.id">
          <td>{{ producto.id }}</td>
          <td>{{ producto.nombre }}</td>
          <td>{{ producto.descripcion }}</td>
          <td>${{ producto.precio }}</td>
          <td>{{ producto.stock }}</td>
          <td>
            <button @click="editarProducto(producto)">
              Editar
            </button>

            <button @click="eliminarProducto(producto.id)">
              Eliminar
            </button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script setup>
import { onMounted, ref } from 'vue'
import ProductoForm from './ProductoForm.vue'
import { getProductos, deleteProducto } from '../services/productoService'

const productos = ref([])
const productoSeleccionado = ref(null)
const mensaje = ref('')
const error = ref('')

const cargarProductos = async () => {
  try {
    const respuesta = await getProductos()
    productos.value = respuesta.data
  } catch (e) {
    error.value = 'Error al cargar productos'
  }
}

const editarProducto = (producto) => {
  productoSeleccionado.value = producto
  mensaje.value = ''
  error.value = ''
}

const productoGuardado = async () => {
  productoSeleccionado.value = null
  mensaje.value = 'Producto guardado correctamente'
  error.value = ''
  await cargarProductos()
}

const cancelarEdicion = () => {
  productoSeleccionado.value = null
}

const eliminarProducto = async (id) => {
  if (!confirm('¿Eliminar producto?')) return

  try {
    await deleteProducto(id)
    mensaje.value = 'Producto eliminado correctamente'
    error.value = ''
    await cargarProductos()
  } catch (e) {
    error.value = 'Error al eliminar'
    mensaje.value = ''
  }
}

onMounted(() => {
  cargarProductos()
})
</script>
<style scoped>
h1 {
  text-align: center;
}

table {
  width: 100%;
  border-collapse: collapse;
  margin-top: 20px;
}

th {
  background: #222;
  color: white;
}

td,
th {
  padding: 10px;
  text-align: center;
}

button {
  margin: 3px;
  padding: 6px 10px;
  cursor: pointer;
}
</style>