<template>
  <Head title="Users"/>
  <AuthenticatedLayout>
    <template #header>
      <h2 class="text-xl font-semibold leading-tight text-gray-800">
        Users
      </h2>
    </template>
    <div class="p-8">
      <h1 class="text-2xl font-bold mb-4">Список пользователей</h1>
      <button @click="openCreateModal" class="bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-600">
        Создать нового пользователя
      </button>

      <UserTable :users="users" @edit="openEditModal" @delete="deleteUser"/>

      <Modal :visible="isModalOpen" @close="closeModal">
        <UserForm :user="modalUser" :availableWines="wines" :errors="validationErrors" @submit="saveUser" @close="closeModal"/>
      </Modal>
    </div>
  </AuthenticatedLayout>
</template>

<script>
import {Head, router} from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import UserTable from '@/Components/UserTable.vue';
import Modal from '@/Components/Modal.vue';
import UserForm from '@/Components/UserForm.vue';

export default {
  components: {Head, AuthenticatedLayout, UserTable, Modal, UserForm},
  props: {users: Array, wines: Array},
  data() {
    return {
      isModalOpen: false,
      modalUser: null,
      validationErrors: {},
    };
  },
  methods: {
    openCreateModal() {
      this.modalUser = null;
      this.isModalOpen = true;
      this.validationErrors = {};
    },
    openEditModal(user) {
      this.modalUser = JSON.parse(JSON.stringify(user));
      this.isModalOpen = true;
      this.validationErrors = {};
    },
    closeModal() {
      this.isModalOpen = false;
      this.modalUser = null;
      this.validationErrors = {};
    },
    saveUser(user) {
      this.validationErrors = {};
      const request = user.id
          ? router.put(`/users/${user.id}`, { ...user, wines: user.wine_ids })
          : router.post('/users', user);

      request.then(() => {
        alert(`Пользователь ${user.id ? 'обновлен' : 'создан'} успешно!`);
        this.closeModal();
      }).catch(error => {
        if (error.response && error.response.status === 422) {
          this.validationErrors = error.response.data.errors;
        } else {
          console.error("Ошибка сохранения пользователя", error);
        }
      });
    },
  }
};
</script>
