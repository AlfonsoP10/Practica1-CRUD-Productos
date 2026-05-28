<template>
  <form @submit.prevent="guardarProducto">
    <h2>{{ productoEditar ? 'Editar Producto' : 'Agregar Producto' }}</h2>

    <div>
      <label>Nombre:</label>
      <input v-model="form.nombre" type="text" required>
    </div>

    <div>
      <label>Descripción:</label>
      <input v-model="form.descripcion" type="text">
    </div>

    <div>
      <label>Precio:</label>
      <input v-model="form.precio" type="number" step="0.01" required>
    </div>

    <div>
      <label>Stock:</label>
      <input v-model="form.stock" type="number" required>
    </div>

    <button type="submit">
      {{ productoEditar ? 'Actualizar' : 'Guardar' }}
    </button>

    <button v-if="productoEditar" type="button" @click="cancelar">
      Cancelar
    </button>
  </form>
</template>

<script setup>
import { reactive, watch } from 'vue'
import { createProducto, updateProducto } from '../services/productoService'

const props = defineProps({
  productoEditar: {
    type: Object,
    default: null,
  },
})

const emit = defineEmits(['productoGuardado', 'cancelarEdicion'])

const form = reactive({
  nombre: '',
  descripcion: '',
  precio: '',
  stock: '',
})

watch(
  () => props.productoEditar,
  (producto) => {
    if (producto) {
      form.nombre = producto.nombre
      form.descripcion = producto.descripcion
      form.precio = producto.precio
      form.stock = producto.stock
    }
  }
)

const limpiarFormulario = () => {
  form.nombre = ''
  form.descripcion = ''
  form.precio = ''
  form.stock = ''
}

const guardarProducto = async () => {
  try {
    if (props.productoEditar) {
      await updateProducto(props.productoEditar.id, form)
    } else {
      await createProducto(form)
    }

    limpiarFormulario()
    emit('productoGuardado')
  } catch (error) {
    alert('Error al guardar producto')
  }
}

const cancelar = () => {
  limpiarFormulario()
  emit('cancelarEdicion')
}
</script>
<style scoped>
form {
  background: #f1f1f1;
  padding: 15px;
  border-radius: 8px;
  margin-bottom: 20px;
}

div {
  margin-bottom: 10px;
}

input {
  width: 100%;
  padding: 7px;
  box-sizing: border-box;
}

button {
  margin-right: 8px;
  padding: 7px 12px;
  cursor: pointer;
}
</style>