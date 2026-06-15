<template>
  <div v-if="meta && meta.last_page > 1" class="paginacion">
    <button :disabled="meta.current_page === 1" @click="$emit('cambio-pagina', 1)">
      Primero
    </button>

    <button :disabled="meta.current_page === 1" @click="$emit('cambio-pagina', meta.current_page - 1)">
      Anterior
    </button>

    <span>
      Página {{ meta.current_page }} de {{ meta.last_page }}
    </span>

    <button :disabled="meta.current_page === meta.last_page" @click="$emit('cambio-pagina', meta.current_page + 1)">
      Siguiente
    </button>

    <button :disabled="meta.current_page === meta.last_page" @click="$emit('cambio-pagina', meta.last_page)">
      Último
    </button>
  </div>
</template>

<script setup>
defineProps({
  meta: {
    type: Object,
    required: true,
  },
})

defineEmits(['cambio-pagina'])
</script>

<style scoped>
.paginacion {
  margin-top: 30px;
  display: flex;
  justify-content: center;
  align-items: center;
  gap: 12px;
}

button {
  padding: 8px 14px;
  border: 1px solid #ddd;
  background: white;
  border-radius: 8px;
  cursor: pointer;
}

button:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}

span {
  font-weight: 600;
}
</style>