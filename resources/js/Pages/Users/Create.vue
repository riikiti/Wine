<template>
  <div>
    <h1>Create User</h1>
    <form @submit.prevent="createUser">
      <input v-model="form.name" placeholder="Name" required />
      <input v-model="form.phone" placeholder="Phone" />
      <input v-model="form.birth_date" type="date" placeholder="Birth Date" />
      <input v-model="form.address" placeholder="Address" />
      <input v-model="form.email" placeholder="Email" type="email" />
      <input v-model="form.password" placeholder="Password" type="password" />
      <button type="submit">Create</button>
    </form>
  </div>
</template>

<script>
import { Inertia } from '@inertiajs/inertia';

export default {
  data() {
    return {
      form: {
        name: '',
        phone: '',
        birth_date: '',
        address: '',
        email: '',
        password: '',
      },
    };
  },
  methods: {
    createUser() {
      Inertia.post('/users', this.form);
    },
  },
};
</script>
