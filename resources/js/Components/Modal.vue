<template>
  <div v-if="visible" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center">
    <div class="bg-white p-4 rounded shadow-lg">
      <slot />
      <button @click="$emit('close')" class="mt-4 bg-gray-400 text-white py-1 px-3 rounded hover:bg-gray-500">
        Закрыть
      </button>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    visible: {
      type: Boolean,
      required: true,
    },
  },
};
</script>
