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
        <UserForm :user="modalUser" :availableWines="wines" @submit="saveUser" @close="closeModal"/>
      </Modal>

    </div>
  </AuthenticatedLayout>
</template>

<script>
import {Head} from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import UserTable from '@/Components/UserTable.vue';
import Modal from '@/Components/Modal.vue';
import UserForm from '@/Components/UserForm.vue';
import {router} from '@inertiajs/vue3';

export default {
  components: {Head, AuthenticatedLayout, UserTable, Modal, UserForm},
  props: {users: Array, wines: Array},
  data() {
    return {
      isModalOpen: false,
      modalUser: null,
    };
  },
  methods: {
    openCreateModal() {
      this.modalUser = {id: null, name: '', phone: ''};
      this.isModalOpen = true;
    },
    openEditModal(user) {
      this.modalUser = {...user};
      this.isModalOpen = true;
    },
    closeModal() {
      this.isModalOpen = false;
    },
    saveUser(user) {
      if (user.id) {
        router.put(`/users/${user.id}`, user);
      } else {
        router.post('/users', user);
      }
      this.closeModal();
    },
    deleteUser(id) {
      if (confirm('Вы уверены, что хотите удалить этого пользователя?')) {
        router.delete(`/users/${id}`);
      }
    },
  },
};
</script>

