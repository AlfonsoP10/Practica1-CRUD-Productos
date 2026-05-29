import { defineStore } from 'pinia'

export const useCarritoStore = defineStore('carrito', {
  state: () => ({
    items: JSON.parse(localStorage.getItem('carrito') || '[]')
  }),

  getters: {
    totalItems: (state) =>
      state.items.reduce((s, i) => s + i.cantidad, 0),

    totalPrecio: (state) =>
      state.items.reduce((s, i) => s + i.precio * i.cantidad, 0),

    cantidadDeProducto: (state) => (id) =>
      state.items.find(i => i.id === id)?.cantidad || 0,
  },

  actions: {
    guardar() {
      localStorage.setItem('carrito', JSON.stringify(this.items))
    },

    agregar(producto) {
      const item = this.items.find(i => i.id === producto.id)

      if (item) {
        item.cantidad++
      } else {
        this.items.push({
          id: producto.id,
          nombre: producto.nombre,
          precio: Number(producto.precio),
          imagen_url: producto.imagen_url || null,
          cantidad: 1
        })
      }

      this.guardar()
    },

    quitar(id) {
      this.items = this.items.filter(i => i.id !== id)
      this.guardar()
    },

    cambiarCantidad(id, cantidad) {
      const item = this.items.find(i => i.id === id)

      if (!item) return

      if (cantidad <= 0) {
        this.quitar(id)
      } else {
        item.cantidad = cantidad
        this.guardar()
      }
    },

    vaciar() {
      this.items = []
      this.guardar()
    }
  }
})