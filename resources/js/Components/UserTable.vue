<template>
  <div class="mt-4">
    <table class="min-w-full table-auto border-collapse border border-gray-200">
      <thead class="bg-gray-100">
      <tr>
        <th class="border border-gray-300 px-4 py-2">ID</th>
        <th class="border border-gray-300 px-4 py-2">Имя</th>
        <th class="border border-gray-300 px-4 py-2">Телефон</th>
        <th class="border border-gray-300 px-4 py-2">Дата рождения</th>
        <th class="border border-gray-300 px-4 py-2">Адрес</th>
        <th class="border border-gray-300 px-4 py-2">Email</th>
        <th class="border border-gray-300 px-4 py-2">Вина</th>
        <th class="border border-gray-300 px-4 py-2">Действия</th>
      </tr>
      </thead>
      <tbody>
      <tr v-for="user in paginatedUsers" :key="user.id" class="hover:bg-gray-50">
        <td class="border border-gray-300 px-4 py-2">{{ user.id }}</td>
        <td class="border border-gray-300 px-4 py-2">{{ user.name }}</td>
        <td class="border border-gray-300 px-4 py-2">{{ user.phone }}</td>
        <td class="border border-gray-300 px-4 py-2">{{ user.birth_date }}</td>
        <td class="border border-gray-300 px-4 py-2">{{ user.address }}</td>
        <td class="border border-gray-300 px-4 py-2">{{ user.email }}</td>
        <td class="border border-gray-300 px-4 py-2">
            <span v-if="user.wines.length">
              {{ user.wines.map(wine => wine.name).join(', ') }}
            </span>
          <span v-else class="text-gray-400">Нет вин</span>
        </td>
        <td class="border border-gray-300 px-4 py-2 flex gap-2">
          <button @click="$emit('edit', user)" class="bg-yellow-500 text-white py-1 px-3 rounded hover:bg-yellow-600">
            Редактировать
          </button>
          <button @click="$emit('delete', user.id)" class="bg-red-500 text-white py-1 px-3 rounded hover:bg-red-600">
            Удалить
          </button>
        </td>
      </tr>
      </tbody>
    </table>

    <!-- Пагинация -->
    <div class="flex justify-between items-center mt-4">
      <button
          @click="prevPage"
          :disabled="currentPage === 1"
          class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400 disabled:opacity-50"
      >
        Предыдущая
      </button>

      <span>Страница {{ currentPage }} из {{ totalPages }}</span>

      <button
          @click="nextPage"
          :disabled="currentPage === totalPages"
          class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400 disabled:opacity-50"
      >
        Следующая
      </button>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    users: Array
  },
  data() {
    return {
      currentPage: 1,
      perPage: 5
    };
  },
  computed: {
    totalPages() {
      return Math.ceil(this.users.length / this.perPage);
    },
    paginatedUsers() {
      const start = (this.currentPage - 1) * this.perPage;
      return this.users.slice(start, start + this.perPage);
    }
  },
  methods: {
    nextPage() {
      if (this.currentPage < this.totalPages) {
        this.currentPage++;
      }
    },
    prevPage() {
      if (this.currentPage > 1) {
        this.currentPage--;
      }
    }
  }
};
</script>
