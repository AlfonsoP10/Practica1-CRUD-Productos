<template>
  <form @submit.prevent="guardarProducto">
    <h2>Añadir Producto</h2>

    <p v-if="mensaje" class="mensaje">{{ mensaje }}</p>
    <p v-if="error" class="error">{{ error }}</p>

    <InputField
      label="Nombre"
      name="nombre"
      v-model="nombre"
      :error="errors.nombre || erroresServidor.nombre?.[0]"
    />

    <InputField
      label="Descripción"
      name="descripcion"
      v-model="descripcion"
      :error="errors.descripcion || erroresServidor.descripcion?.[0]"
    />

    <InputField
      label="Precio"
      name="precio"
      type="number"
      v-model="precio"
      :error="errors.precio || erroresServidor.precio?.[0]"
    />

    <InputField
      label="Stock"
      name="stock"
      type="number"
      v-model="stock"
      :error="errors.stock || erroresServidor.stock?.[0]"
    />

    <div>
      <label>Categoría:</label>
      <select v-model="categoria_id">
        <option value="">Sin categoría</option>
        <option
          v-for="cat in categorias"
          :key="cat.id"
          :value="cat.id"
        >
          {{ cat.nombre }}
        </option>
      </select>

      <span v-if="erroresServidor.categoria_id" class="error-msg">
        {{ erroresServidor.categoria_id[0] }}
      </span>
    </div>

    <div>
      <label>Imagen:</label>
      <input type="file" accept="image/*" @change="onImageChange">

      <span v-if="erroresServidor.imagen" class="error-msg">
        {{ erroresServidor.imagen[0] }}
      </span>
    </div>

    <div v-if="preview">
      <p>Vista previa:</p>
      <img :src="preview" alt="Preview" class="preview">
    </div>

    <button type="submit" :disabled="cargando">
      {{ cargando ? 'Guardando...' : 'Guardar' }}
    </button>
  </form>
</template>

<script setup>
import axios from 'axios'
import { onMounted, ref } from 'vue'
import { useForm, useField } from 'vee-validate'
import { productoSchema } from '@/schemas/productoSchema'
import InputField from '@/components/InputField.vue'
import { createProducto } from '../services/productoService'

const emit = defineEmits(['productoGuardado', 'productoCreado'])

const categorias = ref([])
const imagen = ref(null)
const preview = ref(null)
const cargando = ref(false)
const mensaje = ref('')
const error = ref('')
const erroresServidor = ref({})

const { handleSubmit, errors, resetForm } = useForm({
  validationSchema: productoSchema,
  initialValues: {
    nombre: '',
    descripcion: '',
    precio: '',
    stock: '',
    categoria_id: '',
  },
})

const { value: nombre } = useField('nombre')
const { value: descripcion } = useField('descripcion')
const { value: precio } = useField('precio')
const { value: stock } = useField('stock')
const { value: categoria_id } = useField('categoria_id')

const cargarCategorias = async () => {
  try {
    const respuesta = await axios.get('http://localhost:8000/api/categorias')
    categorias.value = respuesta.data.data
  } catch (e) {
  mostrarError(e.response?.data?.message || 'Error al crear producto')  }
}

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
  resetForm({
    values: {
      nombre: '',
      descripcion: '',
      precio: '',
      stock: '',
      categoria_id: '',
    },
  })

  imagen.value = null
  preview.value = null
  erroresServidor.value = {}
}

const crearFormData = (values) => {
  const fd = new FormData()

  fd.append('nombre', values.nombre)
  fd.append('descripcion', values.descripcion || '')
  fd.append('precio', values.precio)
  fd.append('stock', values.stock)

  if (values.categoria_id) {
    fd.append('categoria_id', values.categoria_id)
  }

  if (imagen.value) {
    fd.append('imagen', imagen.value)
  }

  return fd
}

const guardarProducto = handleSubmit(async (values) => {
  try {
    cargando.value = true
    error.value = ''
    erroresServidor.value = {}

    const fd = crearFormData(values)

    await createProducto(fd)

    mostrarMensaje('Producto creado correctamente')
    limpiarFormulario()

    emit('productoGuardado')
    emit('productoCreado')
  } catch (e) {
    console.error(e)

    if (e.response?.status === 422) {
      erroresServidor.value = e.response.data.errors
      return
    }

    mostrarError('Error al crear producto')
  } finally {
    cargando.value = false
  }
})

onMounted(() => {
  cargarCategorias()
})
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

select {
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

.error,
.error-msg {
  color: red;
  font-weight: bold;
  font-size: 14px;
}
</style>