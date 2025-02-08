<template>
  <Head title="Dashboard"/>

  <AuthenticatedLayout>
    <template #header>
      <h2
          class="text-xl font-semibold leading-tight text-gray-800"
      >
       Users
      </h2>
    </template>
    <div class="p-8">
      <h1 class="text-2xl font-bold mb-4">Список пользователей</h1>

      <button
          @click="openModal()"
          class="bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-600"
      >
        Создать нового пользователя
      </button>

      <div class="mt-4">
        <table class="min-w-full table-auto border-collapse border border-gray-200">
          <thead class="bg-gray-100">
          <tr>
            <th @click="sortBy('id')" class="border border-gray-300 px-4 py-2 cursor-pointer">
              ID
              <span v-if="sortColumn === 'id' && sortOrder === 'asc'">🔼</span>
              <span v-else-if="sortColumn === 'id' && sortOrder === 'desc'">🔽</span>
            </th>
            <th class="border border-gray-300 px-4 py-2">Имя</th>
            <th class="border border-gray-300 px-4 py-2">Телефон</th>
            <th @click="sortBy('birth_date')" class="border border-gray-300 px-4 py-2 cursor-pointer">
              Дата рождения
              <span v-if="sortColumn === 'birth_date' && sortOrder === 'asc'">🔼</span>
              <span v-else-if="sortColumn === 'birth_date' && sortOrder === 'desc'">🔽</span>
            </th>
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
              <span v-if="user.wines.length">{{ user.wines.map(wine => wine.name).join(', ') }}</span>
              <span v-else class="text-gray-400">Нет вин</span>
            </td>
            <td class="border border-gray-300 px-4 py-2 flex gap-2">
              <button @click="openModal(user)" class="bg-yellow-500 text-white py-1 px-3 rounded hover:bg-yellow-600">
                Редактировать
              </button>
              <button @click="deleteUser(user.id)" class="bg-red-500 text-white py-1 px-3 rounded hover:bg-red-600">
                Удалить
              </button>
            </td>
          </tr>
          </tbody>
        </table>

        <div class="mt-4 flex justify-between items-center">
          <button @click="prevPage" :disabled="currentPage === 1" class="bg-gray-500 text-white py-1 px-3 rounded hover:bg-gray-600 disabled:opacity-50">
            Предыдущая
          </button>
          <span>Страница {{ currentPage }} из {{ totalPages }}</span>
          <button @click="nextPage" :disabled="currentPage === totalPages" class="bg-gray-500 text-white py-1 px-3 rounded hover:bg-gray-600 disabled:opacity-50">
            Следующая
          </button>
        </div>
      </div>

      <!-- Модальное окно -->
      <div v-if="isModalOpen" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center">
        <div class="bg-white p-8 rounded shadow-lg w-96">
          <h2 class="text-xl font-bold mb-4">{{ modalTitle }}</h2>
          <form @submit.prevent="saveUser">
            <div class="mb-4">
              <label for="name" class="block text-sm font-medium">Имя:</label>
              <input v-model="modalUser.name" id="name" type="text" class="border border-gray-300 rounded w-full p-2" required />
            </div>
            <div class="mb-4">
              <label for="phone" class="block text-sm font-medium">Телефон:</label>
              <input v-model="modalUser.phone" id="phone" type="text" class="border border-gray-300 rounded w-full p-2" />
            </div>
            <div class="flex justify-end">
              <button type="button" @click="closeModal" class="bg-gray-400 text-white py-1 px-3 rounded hover:bg-gray-500 mr-2">Отмена</button>
              <button type="submit" class="bg-blue-500 text-white py-1 px-3 rounded hover:bg-blue-600">Сохранить</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>

</template>

<script>
import {Head, Link} from '@inertiajs/vue3';
import Importcsv from "@/Components/Importcsv.vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";

export default {
  components: {Head, AuthenticatedLayout, Importcsv},
  props: {
    users: Array,
  },
  data() {
    return {
      currentPage: 1,
      perPage: 10,
      sortColumn: 'id',
      sortOrder: 'asc',
      isModalOpen: false,
      modalTitle: '',
      modalUser: {
        id: null,
        name: '',
        phone: '',
      },
    };
  },
  computed: {
    sortedUsers() {
      return [...this.users].sort((a, b) => {
        if (this.sortOrder === 'asc') {
          return a[this.sortColumn] > b[this.sortColumn] ? 1 : -1;
        } else {
          return a[this.sortColumn] < b[this.sortColumn] ? 1 : -1;
        }
      });
    },
    paginatedUsers() {
      const start = (this.currentPage - 1) * this.perPage;
      return this.sortedUsers.slice(start, start + this.perPage);
    },
    totalPages() {
      return Math.ceil(this.users.length / this.perPage);
    },
  },
  methods: {
    deleteUser(id) {
      if (confirm('Вы уверены, что хотите удалить этого пользователя?')) {
        Inertia.delete(`/users/${id}`);
      }
    },
    sortBy(column) {
      if (this.sortColumn === column) {
        this.sortOrder = this.sortOrder === 'asc' ? 'desc' : 'asc';
      } else {
        this.sortColumn = column;
        this.sortOrder = 'asc';
      }
    },
    nextPage() {
      if (this.currentPage < this.totalPages) {
        this.currentPage++;
      }
    },
    prevPage() {
      if (this.currentPage > 1) {
        this.currentPage--;
      }
    },
    openModal(user = null) {
      this.isModalOpen = true;
      if (user) {
        this.modalTitle = 'Редактировать пользователя';
        this.modalUser = { ...user };
      } else {
        this.modalTitle = 'Создать пользователя';
        this.modalUser = { id: null, name: '', phone: '' };
      }
    },
    closeModal() {
      this.isModalOpen = false;
    },
    saveUser() {
      if (this.modalUser.id) {
        Inertia.put(`/users/${this.modalUser.id}`, this.modalUser);
      } else {
        Inertia.post('/users', this.modalUser);
      }
      this.closeModal();
    },
  },
};
</script>

<style scoped>
.cursor-pointer {
  cursor: pointer;
}
</style>
