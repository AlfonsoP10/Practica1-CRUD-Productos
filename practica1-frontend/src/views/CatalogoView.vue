<template>
  <div class="catalogo-container">
    <h1 class="titulo-pagina">Catálogo de Productos</h1>

    <div class="buscador-wrapper">
      <span class="lupa-icono">🔍</span>
      <input
        v-model="busqueda"
        type="text"
        placeholder="Buscar producto por nombre..."
        class="buscador"
      >
    </div>

    <div v-if="cargando" class="estado-mensaje cargando">
      <div class="spinner"></div>
      <p>Cargando productos...</p>
    </div>

    <div v-if="error" class="estado-mensaje error">
      <p>⚠️ {{ error }}</p>
    </div>

    <div v-if="!cargando && !error" class="grid-productos">
      <div
        v-for="producto in productosPaginados"
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
          <h2 class="producto-titulo" :title="producto.nombre">{{ producto.nombre }}</h2>
          <p class="producto-descripcion">{{ producto.descripcion }}</p>
          
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

    <div v-if="totalPaginas > 1 && !cargando" class="paginacion-container">
      <button
        :disabled="paginaActual === 1"
        @click="paginaActual--"
        class="btn-paginacion"
      >
        &larr; Anterior
      </button>

      <span class="info-paginas">
        Página <strong>{{ paginaActual }}</strong> de {{ totalPaginas }}
      </span>

      <button
        :disabled="paginaActual === totalPaginas"
        @click="paginaActual++"
        class="btn-paginacion"
      >
        Siguiente &rarr;
      </button>
    </div>
  </div>
</template>

<script setup>
import { computed, onMounted, ref, watch } from 'vue'
import { getProductos } from '@/services/productoService'
import { useCarritoStore } from '@/stores/carrito'

const carrito = useCarritoStore()

const productos = ref([])
const busqueda = ref('')
const cargando = ref(false)
const error = ref('')

const paginaActual = ref(1)
const productosPorPagina = 8 // Cambiado a 8 para que arme filas simétricas de 4 o 2 columnas

const cargarProductos = async () => {
  try {
    cargando.value = true
    error.value = ''

    const respuesta = await getProductos()
    productos.value = respuesta.data
  } catch (e) {
    error.value = 'Error al cargar los productos. Por favor, reintenta más tarde.'
  } finally {
    cargando.value = false
  }
}

const productosFiltrados = computed(() =>
  productos.value.filter((p) =>
    p.nombre.toLowerCase().includes(busqueda.value.toLowerCase())
  )
)

const totalPaginas = computed(() =>
  Math.ceil(productosFiltrados.value.length / productosPorPagina)
)

const productosPaginados = computed(() => {
  const inicio = (paginaActual.value - 1) * productosPorPagina
  const fin = inicio + productosPorPagina

  return productosFiltrados.value.slice(inicio, fin)
})

watch(busqueda, () => {
  paginaActual.value = 1
})

onMounted(() => {
  cargarProductos()
})
</script>

<style scoped>
/* Contenedor General */
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

/* Buscador Estilizado */
.buscador-wrapper {
  position: relative;
  max-width: 450px;
  margin-bottom: 32px;
}

.lupa-icono {
  position: absolute;
  left: 14px;
  top: 50%;
  transform: translateY(-50%);
  font-size: 16px;
  color: #9ca3af;
}

.buscador {
  width: 100%;
  padding: 12px 16px 12px 42px;
  font-size: 15px;
  border: 1px solid #e5e7eb;
  border-radius: 12px;
  background-color: #fff;
  box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
  transition: all 0.2s ease;
  outline: none;
}

.buscador:focus {
  border-color: #4f46e5;
  box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1);
}

/* Grid de Tarjetas */
.grid-productos {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
  gap: 24px;
}

/* Tarjeta del Producto */
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

/* Imagen de producto */
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

/* Cuerpo de la Tarjeta */
.card-cuerpo {
  padding: 16px;
  display: flex;
  flex-direction: column;
  flex-grow: 1; /* Empuja los botones hacia el fondo */
}

.producto-titulo {
  font-size: 17px;
  font-weight: 600;
  margin: 0 0 8px 0;
  color: #1f2937;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis; /* Corta textos excesivamente largos con puntos suspensivos */
}

.producto-descripcion {
  font-size: 14px;
  color: #6b7280;
  margin: 0 0 16px 0;
  display: -webkit-box;
  -webkit-line-clamp: 2; /* Muestra máximo dos líneas de descripción */
  -webkit-box-orient: vertical;
  overflow: hidden;
  line-height: 1.4;
  height: 2.8em; /* Mantiene la misma altura de caja fija */
}

.meta-info {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-top: auto; /* Alínea la info de precios abajo */
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

/* Acciones Inferiores (Botones) */
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

/* Botón cuando ya está en el carrito */
.btn-agregar.en-carrito {
  background: #10b981;
}

.btn-agregar.en-carrito:hover {
  background: #059669;
}

/* Paginación */
.paginacion-container {
  margin-top: 40px;
  display: flex;
  justify-content: center;
  align-items: center;
  gap: 20px;
}

.btn-paginacion {
  border: 1px solid #e5e7eb;
  background: white;
  padding: 8px 16px;
  font-size: 14px;
  font-weight: 500;
  border-radius: 8px;
  cursor: pointer;
  color: #374151;
  box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
  transition: all 0.2s;
}

.btn-paginacion:hover:not(:disabled) {
  border-color: #4f46e5;
  color: #4f46e5;
  background: #f9fafb;
}

.btn-paginacion:disabled {
  opacity: 0.5;
  cursor: not-allowed;
  background: #f3f4f6;
}

.info-paginas {
  font-size: 14px;
  color: #6b7280;
}

/* Estados de carga */
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