<template>
  <form @submit.prevent="handleSubmit">
    <div class="mb-4">
      <label for="name" class="block text-sm font-medium">Имя:</label>
      <input v-model="localUser.name" id="name" type="text" class="border border-gray-300 rounded w-full p-2" required />
    </div>

    <div class="mb-4">
      <label for="phone" class="block text-sm font-medium">Телефон:</label>
      <input v-model="localUser.phone" id="phone" type="text" class="border border-gray-300 rounded w-full p-2" />
    </div>

    <div class="mb-4">
      <label for="address" class="block text-sm font-medium">Адрес:</label>
      <input v-model="localUser.address" id="address" type="text" class="border border-gray-300 rounded w-full p-2" />
    </div>

    <div class="mb-4">
      <label for="birthdate" class="block text-sm font-medium">Дата рождения:</label>
      <input v-model="localUser.birth_date" id="birthdate" type="date" class="border border-gray-300 rounded w-full p-2" />
    </div>

    <div class="mb-4 relative">
      <label class="block text-sm font-medium mb-1">Вина:</label>
      <div @click="toggleDropdown" class="border border-gray-300 rounded w-full p-2 cursor-pointer">
        {{ selectedWines.length > 0 ? selectedWinesNames : 'Выберите вина' }}
      </div>
      <ul v-if="dropdownOpen" class="absolute z-10 w-full border bg-white shadow-md max-h-40 overflow-y-auto">
        <li v-for="wine in availableWines" :key="wine.id" class="px-4 py-2 hover:bg-gray-100 cursor-pointer flex items-center">
          <input type="checkbox" :value="wine.id" v-model="selectedWines" class="mr-2" />
          {{ wine.name }}
        </li>
      </ul>
    </div>

    <div class="flex justify-end">
      <button type="button" @click="$emit('close')" class="bg-gray-400 text-white py-1 px-3 rounded hover:bg-gray-500 mr-2">
        Отмена
      </button>
      <button type="submit" class="bg-blue-500 text-white py-1 px-3 rounded hover:bg-blue-600">
        Сохранить
      </button>
    </div>
  </form>
</template>

<script>
export default {
  props: {
    user: Object,
    availableWines: Array,
  },
  data() {
    return {
      localUser: { ...this.user },
      selectedWines: this.user.wines ? this.user.wines.map(wine => wine.id) : [],
      dropdownOpen: false,
    };
  },
  computed: {
    selectedWinesNames() {
      return this.availableWines
          .filter(wine => this.selectedWines.includes(wine.id))
          .map(wine => wine.name)
          .join(', ');
    },
  },
  methods: {
    handleSubmit() {
      const updatedUser = {
        ...this.localUser,
        wine_ids: this.selectedWines,
      };
      this.$emit('submit', updatedUser);
    },
    toggleDropdown() {
      this.dropdownOpen = !this.dropdownOpen;
    },
  },
};
</script>

<style>
/* Опциональный стиль для плавного скролла */
.max-h-40 {
  max-height: 10rem;
}
</style>
