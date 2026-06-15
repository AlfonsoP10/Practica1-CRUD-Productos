<template>
  <div class="detalle-container">
    <div class="navegacion-superior">
      <button @click="volver" class="btn-volver">
        &larr; Volver al catálogo
      </button>
    </div>

    <div v-if="error" class="estado-mensaje error">
      <span class="error-icono">⚠️</span>
      <div class="error-texto">
        <p class="error-titulo">Error de comunicación</p>
        <p>{{ error }}</p>
      </div>
    </div>

    <div v-else-if="producto" class="detalle-wrapper">
      
      <div class="columna-imagen">
        <img
          v-if="producto.imagen_url"
          :src="producto.imagen_url"
          :alt="producto.nombre"
          class="producto-imagen"
        >
        <div v-else class="sin-imagen">
          <span>Imagen no disponible</span>
        </div>
      </div>

      <div class="columna-info">
        <div class="info-principal">
          <h1 class="producto-nombre">{{ producto.nombre }}</h1>
          
          <div class="badges-meta">
            <span class="badge-precio">${{ Number(producto.precio).toFixed(2) }}</span>
            <span class="badge-stock" :class="{ 'sin-stock': producto.stock === 0, 'bajo-stock': producto.stock <= 5 }">
              {{ producto.stock > 0 ? `Disponibles: ${producto.stock}` : 'Agotado' }}
            </span>
          </div>
        </div>

        <div class="info-descripcion">
          <h3>Descripción del producto</h3>
          <p>{{ producto.descripcion || 'Este producto no cuenta con una descripción detallada en este momento.' }}</p>
        </div>

        <div class="info-acciones">
          <div class="garantia-tag">
            <span class="garantia-icono">🛡️</span>
            <p>Compra garantizada y soporte técnico incluido.</p>
          </div>
        </div>
      </div>

    </div>

    <div v-else class="skeleton-wrapper">
      <div class="skeleton-imagen"></div>
      <div class="skeleton-info">
        <div class="skeleton-linea titulo"></div>
        <div class="skeleton-linea precio"></div>
        <div class="skeleton-linea parrafo"></div>
        <div class="skeleton-linea parrafo"></div>
      </div>
    </div>
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
    error.value = ''
    const respuesta = await axios.get(`http://localhost:8000/api/v1/productos/${props.id}`)
    producto.value = respuesta.data
  } catch (e) {
    error.value = 'No se pudo conectar con el servidor. Asegúrate de que el backend en Laravel esté corriendo correctamente.'
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
/* Contenedor Base */
.detalle-container {
  max-width: 1000px;
  margin: 40px auto;
  padding: 0 20px;
  font-family: system-ui, -apple-system, sans-serif;
  color: #333;
}

/* Navegación */
.navegacion-superior {
  margin-bottom: 24px;
}

.btn-volver {
  background: transparent;
  border: none;
  color: #4b5563;
  font-size: 14px;
  font-weight: 600;
  cursor: pointer;
  padding: 8px 12px;
  border-radius: 8px;
  transition: all 0.2s;
}

.btn-volver:hover {
  color: #4f46e5;
  background-color: #f3f4f6;
}

/* Layout en Dos Columnas */
.detalle-wrapper {
  display: grid;
  grid-template-columns: 1fr;
  gap: 40px;
  background: #ffffff;
  border: 1px solid #f3f4f6;
  border-radius: 20px;
  padding: 32px;
  box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.02), 0 2px 4px -1px rgba(0, 0, 0, 0.02);
}

@media (min-width: 768px) {
  .detalle-wrapper {
    grid-template-columns: 1fr 1fr; /* Divide la pantalla a la mitad */
    align-items: start;
  }
}

/* Columna de la Imagen */
.columna-imagen {
  width: 100%;
  height: 380px;
  background-color: #f9fafb;
  border-radius: 16px;
  overflow: hidden;
  border: 1px solid #f3f4f6;
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
  font-size: 15px;
  font-weight: 500;
}

/* Columna de Información */
.columna-info {
  display: flex;
  flex-direction: column;
  gap: 28px;
}

.info-principal {
  border-bottom: 1px solid #f3f4f6;
  padding-bottom: 20px;
}

.producto-nombre {
  font-size: 28px;
  font-weight: 700;
  color: #111827;
  margin: 0 0 16px 0;
  line-height: 1.2;
}

.badges-meta {
  display: flex;
  align-items: center;
  gap: 16px;
}

.badge-precio {
  font-size: 26px;
  font-weight: 800;
  color: #4f46e5;
}

.badge-stock {
  font-size: 13px;
  font-weight: 600;
  background-color: #ecfdf5;
  color: #065f46;
  padding: 6px 12px;
  border-radius: 8px;
}

.badge-stock.bajo-stock {
  background-color: #fff7ed;
  color: #9a3412;
}

.badge-stock.sin-stock {
  background-color: #fef2f2;
  color: #991b1b;
}

/* Sección de descripción */
.info-descripcion h3 {
  font-size: 15px;
  font-weight: 700;
  color: #374151;
  margin: 0 0 8px 0;
  text-transform: uppercase;
  letter-spacing: 0.05em;
}

.info-descripcion p {
  font-size: 15px;
  color: #4b5563;
  line-height: 1.6;
  margin: 0;
}

/* Tags de Garantía extra */
.garantia-tag {
  display: flex;
  align-items: center;
  gap: 10px;
  background-color: #f8fafc;
  border: 1px solid #e2e8f0;
  padding: 12px 16px;
  border-radius: 12px;
}

.garantia-icono {
  font-size: 18px;
}

.garantia-tag p {
  margin: 0;
  font-size: 13px;
  color: #475569;
  font-weight: 500;
}

/* Mensaje de Error Completo */
.estado-mensaje.error {
  display: flex;
  gap: 16px;
  background-color: #fef2f2;
  border: 1px solid #fee2e2;
  padding: 20px;
  border-radius: 16px;
  align-items: flex-start;
}

.error-icono {
  font-size: 24px;
}

.error-titulo {
  font-weight: 700;
  color: #991b1b;
  margin: 0 0 4px 0;
  font-size: 15px;
}

.error-texto p:last-child {
  margin: 0;
  font-size: 14px;
  color: #7f1d1d;
  line-height: 1.4;
}

/* Estilo de Carga Previa (Skeleton Loading) */
.skeleton-wrapper {
  display: grid;
  grid-template-columns: 1fr;
  gap: 40px;
  background: #fff;
  border: 1px solid #f3f4f6;
  border-radius: 20px;
  padding: 32px;
}

@media (min-width: 768px) {
  .skeleton-wrapper {
    grid-template-columns: 1fr 1fr;
  }
}

.skeleton-imagen {
  height: 380px;
  background: #f3f4f6;
  border-radius: 16px;
}

.skeleton-info {
  display: flex;
  flex-direction: column;
  gap: 20px;
}

.skeleton-linea {
  background: linear-gradient(90deg, #f3f4f6 25%, #e5e7eb 50%, #f3f4f6 75%);
  background-size: 200% 100%;
  animation: pulse 1.5s infinite;
  border-radius: 4px;
}

.skeleton-linea.titulo { height: 32px; width: 70%; }
.skeleton-linea.precio { height: 40px; width: 40%; }
.skeleton-linea.parrafo { height: 16px; width: 100%; }

@keyframes pulse {
  0% { background-position: 200% 0; }
  100% { background-position: -200% 0; }
}
</style>