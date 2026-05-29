<template>
  <router-link to="/carrito" class="cart-link">
    <span class="cart-icon">
      🛒
      <span v-if="carrito.totalItems > 0" class="badge">
        {{ carrito.totalItems }}
      </span>
    </span>
  </router-link>
</template>

<script setup>
import { useCarritoStore } from '@/stores/carrito'

const carrito = useCarritoStore()
</script>

<style scoped>
.cart-link {
  text-decoration: none;
  color: inherit;
}

.cart-icon {
  position: relative;
  font-size: 24px;
  display: inline-block;
}

.badge {
  position: absolute;
  top: -8px;
  right: -12px;
  background: red;
  color: white;
  font-size: 12px;
  padding: 2px 6px;
  border-radius: 50%;
}
</style>