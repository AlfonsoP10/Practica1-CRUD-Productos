<template>
  <div class="catalogo-container">
    <h1 class="titulo-pagina">Catálogo de Productos</h1>

    <FiltrosPanel :filtros="filtros" />

    <div v-if="cargando" class="estado-mensaje cargando">
      <div class="spinner"></div>
      <p>Cargando productos...</p>
    </div>

    <div v-if="error" class="estado-mensaje error">
      <p>⚠️ {{ error }}</p>
    </div>

    <div v-if="!cargando && !error" class="grid-productos">
      <div
        v-for="producto in resultado.data"
        :key="producto.id"
        class="producto-card"
      >
        <div class="imagen-contenedor">
          <img
            v-if="producto.imagen_url"
            :src="producto.imagen_url"
            :alt="producto.nombre"
            class="producto-imagen"
          >
          <div v-else class="sin-imagen">
            <span>Sin imagen</span>
          </div>
        </div>

        <div class="card-cuerpo">
          <h2 class="producto-titulo" :title="producto.nombre">
            {{ producto.nombre }}
          </h2>

          <p class="categoria-texto" v-if="producto.categoria">
            {{ producto.categoria.nombre }}
          </p>

          <p class="producto-descripcion">
            {{ producto.descripcion }}
          </p>

          <div class="meta-info">
            <span class="precio">${{ Number(producto.precio).toFixed(2) }}</span>
            <span class="stock" :class="{ 'bajo-stock': producto.stock <= 5 }">
              Stock: {{ producto.stock }}
            </span>
          </div>
        </div>

        <div class="card-acciones">
          <router-link :to="`/catalogo/${producto.id}`" class="btn-detalle">
            Ver detalle
          </router-link>

          <button
            @click="carrito.agregar(producto)"
            class="btn-agregar"
            :class="{ 'en-carrito': carrito.cantidadDeProducto(producto.id) > 0 }"
          >
            <template v-if="carrito.cantidadDeProducto(producto.id) > 0">
              En carrito ({{ carrito.cantidadDeProducto(producto.id) }})
            </template>
            <template v-else>
              Agregar al carrito
            </template>
          </button>
        </div>
      </div>
    </div>

    <p v-if="!cargando && !error && resultado.data.length === 0" class="estado-mensaje">
      No se encontraron productos con esos filtros.
    </p>

    <PaginacionNav
      :meta="metaPaginacion"
      @cambio-pagina="cambiarPagina"
    />
  </div>
</template>

<script setup>
import axios from 'axios'
import { computed, ref, watch } from 'vue'
import { useRoute } from 'vue-router'
import { useCarritoStore } from '@/stores/carrito'
import { useFiltros } from '@/composables/useFiltros'
import FiltrosPanel from '@/components/FiltrosPanel.vue'
import PaginacionNav from '@/components/PaginacionNav.vue'

const carrito = useCarritoStore()
const route = useRoute()
const { filtros } = useFiltros()

const resultado = ref({
  data: [],
})

const cargando = ref(false)
const error = ref('')

const metaPaginacion = computed(() => ({
  current_page: resultado.value.current_page || 1,
  last_page: resultado.value.last_page || 1,
  per_page: resultado.value.per_page || 15,
  total: resultado.value.total || 0,
}))

const obtenerOrden = () => {
  if (filtros.orden === 'precio-asc') {
    return { orden: 'precio', dir: 'asc' }
  }

  if (filtros.orden === 'precio-desc') {
    return { orden: 'precio', dir: 'desc' }
  }

  return { orden: 'nombre', dir: 'asc' }
}

const cargarProductos = async () => {
  try {
    cargando.value = true
    error.value = ''

    const orden = obtenerOrden()

   const respuesta = await axios.get('http://localhost:8000/api/v1/productos', {
      params: {
        busqueda: filtros.busqueda,
        categoria_id: filtros.categoria_id,
        precio_min: filtros.precio_min,
        precio_max: filtros.precio_max,
        page: filtros.pagina,
        orden: orden.orden,
        dir: orden.dir,
        por_pagina: 15,
      },
    })

    resultado.value = respuesta.data
  } catch (e) {
    console.error(e)
    error.value = 'Error al cargar los productos.'
  } finally {
    cargando.value = false
  }
}

const cambiarPagina = (pagina) => {
  filtros.pagina = pagina
}

watch(
  () => route.query,
  () => {
    cargarProductos()
  },
  { immediate: true }
)
</script>

<style scoped>
.catalogo-container {
  max-width: 1200px;
  margin: 40px auto;
  padding: 0 20px;
  font-family: system-ui, -apple-system, sans-serif;
  color: #333;
}

.titulo-pagina {
  font-size: 28px;
  font-weight: 700;
  margin-bottom: 24px;
  color: #111827;
}

.grid-productos {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
  gap: 24px;
}

.producto-card {
  background: #ffffff;
  border: 1px solid #f3f4f6;
  border-radius: 16px;
  display: flex;
  flex-direction: column;
  overflow: hidden;
  box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.02), 0 2px 4px -1px rgba(0, 0, 0, 0.02);
  transition: transform 0.2s, box-shadow 0.2s;
}

.producto-card:hover {
  transform: translateY(-4px);
  box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
}

.imagen-contenedor {
  width: 100%;
  height: 180px;
  background-color: #f3f4f6;
  position: relative;
}

.producto-imagen {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.sin-imagen {
  width: 100%;
  height: 100%;
  display: flex;
  align-items: center;
  justify-content: center;
  color: #9ca3af;
  font-size: 14px;
  font-weight: 500;
}

.card-cuerpo {
  padding: 16px;
  display: flex;
  flex-direction: column;
  flex-grow: 1;
}

.producto-titulo {
  font-size: 17px;
  font-weight: 600;
  margin: 0 0 6px 0;
  color: #1f2937;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

.categoria-texto {
  font-size: 12px;
  color: #4f46e5;
  background: #f5f3ff;
  width: fit-content;
  padding: 4px 8px;
  border-radius: 999px;
  margin: 0 0 10px 0;
  font-weight: 600;
}

.producto-descripcion {
  font-size: 14px;
  color: #6b7280;
  margin: 0 0 16px 0;
  overflow: hidden;
  line-height: 1.4;

  display: -webkit-box;
  line-clamp: 2;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
}

.meta-info {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-top: auto;
  padding-top: 8px;
}

.precio {
  font-size: 20px;
  font-weight: 700;
  color: #111827;
}

.stock {
  font-size: 12px;
  background: #f3f4f6;
  color: #4b5563;
  padding: 4px 8px;
  border-radius: 6px;
  font-weight: 500;
}

.stock.bajo-stock {
  background: #fef2f2;
  color: #dc2626;
}

.card-acciones {
  padding: 0 16px 16px 16px;
  display: flex;
  flex-direction: column;
  gap: 8px;
}

.btn-detalle {
  display: block;
  text-align: center;
  text-decoration: none;
  font-size: 14px;
  font-weight: 500;
  color: #4f46e5;
  background: #f5f3ff;
  padding: 8px;
  border-radius: 8px;
  transition: background 0.2s;
}

.btn-detalle:hover {
  background: #ede9fe;
}

.btn-agregar {
  width: 100%;
  border: none;
  background: #4f46e5;
  color: white;
  padding: 10px;
  font-size: 14px;
  font-weight: 600;
  border-radius: 8px;
  cursor: pointer;
  transition: all 0.2s;
}

.btn-agregar:hover {
  background: #4338ca;
}

.btn-agregar.en-carrito {
  background: #10b981;
}

.btn-agregar.en-carrito:hover {
  background: #059669;
}

.estado-mensaje {
  text-align: center;
  padding: 40px;
  border-radius: 12px;
  font-size: 16px;
}

.estado-mensaje.error {
  background: #fef2f2;
  color: #b91c1c;
}

.spinner {
  margin: 0 auto 12px auto;
  width: 32px;
  height: 32px;
  border: 3px solid #f3f4f6;
  border-top: 3px solid #4f46e5;
  border-radius: 50%;
  animation: spin 0.8s linear infinite;
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}
</style>