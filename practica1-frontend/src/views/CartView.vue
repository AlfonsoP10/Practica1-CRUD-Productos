<template>
  <div class="carrito-container">
    <h1>Carrito de compras</h1>

    <div v-if="carrito.items.length === 0" class="carrito-vacio">
      <div class="icono-vacio">🛒</div>
      <p>Tu carrito está vacío actualmente.</p>
      <router-link to="/catalogo" class="btn-primario">Ir al catálogo</router-link>
    </div>

    <div v-else class="carrito-wrapper">
      <div class="lista-productos">
        <div v-for="item in carrito.items" :key="item.id" class="producto-card">
          <div class="info-producto">
            <img v-if="item.imagen_url" :src="item.imagen_url" :alt="item.nombre" class="producto-imagen">
            <div v-else class="sin-imagen">Sin imagen</div>

            <div class="detalles">
              <h3>{{ item.nombre }}</h3>
              <p class="precio-unitario">Precio: ${{ Number(item.precio).toFixed(2) }}</p>
              <p class="subtotal-movil">Subtotal: ${{ (item.precio * item.cantidad).toFixed(2) }}</p>
            </div>
          </div>

          <div class="controles-acciones">
            <div class="selector-cantidad">
              <button @click="carrito.cambiarCantidad(item.id, item.cantidad - 1)">-</button>
              <span class="cantidad">{{ item.cantidad }}</span>
              <button @click="carrito.cambiarCantidad(item.id, item.cantidad + 1)">+</button>
            </div>

            <div class="subtotal-desktop">
              <span>${{ (item.precio * item.cantidad).toFixed(2) }}</span>
            </div>

            <button class="btn-eliminar" @click="carrito.quitar(item.id)" title="Eliminar artículo">
              &times;
            </button>
          </div>
        </div>
      </div>

      <div class="resumen-compra">
        <h3>Resumen</h3>
        <div class="fila-resumen">
          <span>Total:</span>
          <span class="monto-total">${{ carrito.totalPrecio.toFixed(2) }}</span>
        </div>
        
        <button @click="finalizarCompra" class="btn-checkout">
          Finalizar compra
        </button>
        
        <button @click="vaciarCarrito" class="btn-vaciar">
          Vaciar carrito
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { useCarritoStore } from '@/stores/carrito'

const carrito = useCarritoStore()

const vaciarCarrito = () => {
  if (confirm('¿Seguro que quieres vaciar el carrito?')) {
    carrito.vaciar()
  }
}

const finalizarCompra = () => {
  if (carrito.items.length === 0) {
    alert('El carrito está vacío')
    return
  }
  alert('Compra finalizada correctamente')
  carrito.vaciar()
}
</script>

<style scoped>
.carrito-container {
  max-width: 1000px;
  margin: 40px auto;
  padding: 0 20px;
  font-family: system-ui, -apple-system, sans-serif;
  color: #333;
}

h1 {
  font-size: 28px;
  font-weight: 700;
  margin-bottom: 30px;
  border-bottom: 2px solid #f0f0f0;
  padding-bottom: 15px;
}

/* Estado Vacío */
.carrito-vacio {
  text-align: center;
  padding: 60px 20px;
  background: #f9f9f9;
  border-radius: 16px;
  border: 1px dashed #ddd;
}
.icono-vacio {
  font-size: 48px;
  margin-bottom: 15px;
}
.carrito-vacio p {
  color: #666;
  margin-bottom: 20px;
  font-size: 16px;
}

/* Layout del Carrito Activo */
.carrito-wrapper {
  display: grid;
  grid-template-columns: 1fr;
  gap: 30px;
}

@media (min-width: 768px) {
  .carrito-wrapper {
    grid-template-columns: 2fr 1fr;
    align-items: start;
  }
}

/* Cards de productos */
.lista-productos {
  display: flex;
  flex-direction: column;
  gap: 16px;
}

.producto-card {
  background: #ffffff;
  border: 1px solid #eaeaea;
  padding: 16px;
  border-radius: 12px;
  display: flex;
  flex-direction: column;
  gap: 16px;
  box-shadow: 0 2px 4px rgba(0,0,0,0.02);
  transition: transform 0.2s, box-shadow 0.2s;
}

.producto-card:hover {
  box-shadow: 0 4px 12px rgba(0,0,0,0.05);
}

@media (min-width: 480px) {
  .producto-card {
    flex-direction: row;
    justify-content: space-between;
    align-items: center;
  }
}

.info-producto {
  display: flex;
  align-items: center;
  gap: 16px;
}

.producto-imagen,
.sin-imagen {
  width: 80px;
  height: 80px;
  border-radius: 8px;
  background: #f5f5f5;
  object-fit: cover;
  flex-shrink: 0;
}

.sin-imagen {
  display: flex;
  align-items: center;
  justify-content: center;
  color: #888;
  font-size: 12px;
}

.detalles h3 {
  margin: 0 0 4px 0;
  font-size: 16px;
  font-weight: 600;
}

.precio-unitario {
  margin: 0;
  font-size: 14px;
  color: #666;
}

.subtotal-movil {
  margin: 4px 0 0 0;
  font-size: 14px;
  font-weight: 600;
  color: #4f46e5;
}

@media (min-width: 480px) {
  .subtotal-movil { display: none; }
}

/* Controles de interacción */
.controles-acciones {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 20px;
}

@media (min-width: 480px) {
  .controles-acciones {
    justify-content: flex-end;
  }
}

.selector-cantidad {
  display: flex;
  align-items: center;
  background: #f5f5f7;
  border-radius: 8px;
  padding: 3px;
}

.selector-cantidad button {
  background: none;
  border: none;
  width: 28px;
  height: 28px;
  cursor: pointer;
  font-weight: bold;
  font-size: 16px;
  color: #555;
  border-radius: 6px;
  transition: background 0.2s;
}

.selector-cantidad button:hover {
  background: #e4e4e7;
}

.cantidad {
  width: 32px;
  text-align: center;
  font-size: 14px;
  font-weight: 600;
}

.subtotal-desktop {
  display: none;
  font-weight: 600;
  min-w: 80px;
  text-align: right;
}

@media (min-width: 480px) {
  .subtotal-desktop { display: block; }
}

.btn-eliminar {
  background: none;
  border: none;
  color: #aaa;
  font-size: 22px;
  cursor: pointer;
  padding: 4px 8px;
  border-radius: 6px;
  transition: color 0.2s, background 0.2s;
}

.btn-eliminar:hover {
  color: #ef4444;
  background: #fef2f2;
}

/* Panel lateral de resumen */
.resumen-compra {
  background: #f9f9f9;
  border: 1px solid #f0f0f0;
  padding: 24px;
  border-radius: 16px;
  display: flex;
  flex-direction: column;
  gap: 16px;
}

.resumen-compra h3 {
  margin: 0;
  font-size: 18px;
  border-bottom: 1px solid #eaeaea;
  padding-bottom: 10px;
}

.fila-resumen {
  display: flex;
  justify-content: space-between;
  align-items: center;
  font-size: 15px;
}

.monto-total {
  font-size: 22px;
  font-weight: 700;
  color: #4f46e5;
}

/* Botones del resumen */
.btn-primario,
.btn-checkout {
  display: inline-block;
  text-align: center;
  text-decoration: none;
  background: #4f46e5;
  color: white;
  border: none;
  padding: 12px 20px;
  border-radius: 10px;
  font-weight: 600;
  cursor: pointer;
  transition: background 0.2s;
}

.btn-primario:hover,
.btn-checkout:hover {
  background: #4338ca;
}

.btn-vaciar {
  background: transparent;
  border: none;
  color: #888;
  cursor: pointer;
  font-size: 14px;
  padding: 8px;
  transition: color 0.2s;
}

.btn-vaciar:hover {
  color: #ef4444;
}
</style>