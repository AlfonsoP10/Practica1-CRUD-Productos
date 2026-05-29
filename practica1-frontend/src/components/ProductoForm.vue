<template>
  <form @submit.prevent="guardarProducto">
    <h2>{{ productoEditar ? 'Editar Producto' : 'Agregar Producto' }}</h2>

    <p v-if="mensaje" class="mensaje">
      {{ mensaje }}
    </p>

    <p v-if="error" class="error">
      {{ error }}
    </p>

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

    <div>
      <label>Imagen:</label>
      <input type="file" accept="image/*" @change="onImageChange">
    </div>

    <div v-if="preview">
      <p>Vista previa:</p>
      <img :src="preview" alt="Preview" class="preview">
    </div>

    <button type="submit" :disabled="cargando">
      {{ cargando ? 'Guardando...' : productoEditar ? 'Actualizar' : 'Guardar' }}
    </button>

    <button v-if="productoEditar" type="button" @click="cancelar">
      Cancelar
    </button>
  </form>
</template>

<script setup>
import { reactive, ref, watch } from 'vue'
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

const imagen = ref(null)
const preview = ref(null)
const cargando = ref(false)
const mensaje = ref('')
const error = ref('')

watch(
  () => props.productoEditar,
  (producto) => {
    if (producto) {
      form.nombre = producto.nombre
      form.descripcion = producto.descripcion
      form.precio = producto.precio
      form.stock = producto.stock
      preview.value = producto.imagen_url || null
      imagen.value = null
    }
  }
)

const mostrarMensaje = (texto) => {
  mensaje.value = texto

  setTimeout(() => {
    mensaje.value = ''
  }, 3000)
}

const mostrarError = (texto) => {
  error.value = texto

  setTimeout(() => {
    error.value = ''
  }, 3000)
}

const onImageChange = (e) => {
  const file = e.target.files[0]

  if (!file) return

  if (file.size > 2 * 1024 * 1024) {
    mostrarError('La imagen no debe pesar más de 2MB')
    return
  }

  imagen.value = file
  preview.value = URL.createObjectURL(file)
}

const limpiarFormulario = () => {
  form.nombre = ''
  form.descripcion = ''
  form.precio = ''
  form.stock = ''
  imagen.value = null
  preview.value = null
}

const crearFormData = () => {
  const fd = new FormData()

  fd.append('nombre', form.nombre)
  fd.append('descripcion', form.descripcion || '')
  fd.append('precio', form.precio)
  fd.append('stock', form.stock)

  if (imagen.value) {
    fd.append('imagen', imagen.value)
  }

  return fd
}

const validarFormulario = () => {
  if (!form.nombre || !form.precio || !form.stock) {
    mostrarError('Completa los campos obligatorios')
    return false
  }

  if (Number(form.precio) < 0) {
    mostrarError('El precio no puede ser negativo')
    return false
  }

  if (Number(form.stock) < 0) {
    mostrarError('El stock no puede ser negativo')
    return false
  }

  if (imagen.value && imagen.value.size > 2 * 1024 * 1024) {
    mostrarError('La imagen no debe pesar más de 2MB')
    return false
  }

  return true
}

const guardarProducto = async () => {
  if (!validarFormulario()) return

  try {
    cargando.value = true
    error.value = ''

    const fd = crearFormData()

    if (props.productoEditar) {
      await updateProducto(props.productoEditar.id, fd)
      mostrarMensaje('Producto actualizado correctamente')
    } else {
      await createProducto(fd)
      mostrarMensaje('Producto creado correctamente')
    }

    limpiarFormulario()
    emit('productoGuardado')
  } catch (e) {
    console.error(e)
    mostrarError('Error al guardar producto')
  } finally {
    cargando.value = false
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

button:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}

.preview {
  max-width: 200px;
  border-radius: 8px;
  display: block;
}

.mensaje {
  color: green;
  font-weight: bold;
}

.error {
  color: red;
  font-weight: bold;
}
</style>