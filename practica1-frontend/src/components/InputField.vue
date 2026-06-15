<template>
  <div class="campo">
    <label :for="name">{{ label }}</label>

    <input
      :id="name"
      :name="name"
      :type="type"
      :value="modelValue"
      :placeholder="placeholder"
      :class="{ error: error }"
      @input="$emit('update:modelValue', $event.target.value)"
    />

    <span v-if="error" class="error-msg">
      {{ error }}
    </span>
  </div>
</template>

<script setup>
defineProps({
  label: String,
  name: String,
  modelValue: [String, Number],
  error: String,
  type: {
    type: String,
    default: 'text'
  },
  placeholder: {
    type: String,
    default: ''
  }
})

defineEmits(['update:modelValue'])
</script>

<style scoped>
.campo {
  margin-bottom: 15px;
  display: flex;
  flex-direction: column;
}

label {
  font-weight: bold;
  margin-bottom: 5px;
}

input {
  padding: 10px;
  border: 1px solid #ccc;
  border-radius: 6px;
}

input.error {
  border-color: red;
}

.error-msg {
  color: red;
  font-size: 14px;
  margin-top: 4px;
}
</style>