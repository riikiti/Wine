<template>
  <div>
    <h1>Edit User</h1>
    <form @submit.prevent="updateUser">
      <input v-model="form.name" placeholder="Name" required />
      <input v-model="form.phone" placeholder="Phone" />
      <input v-model="form.birth_date" type="date" placeholder="Birth Date" />
      <input v-model="form.address" placeholder="Address" />
      <input v-model="form.email" placeholder="Email" type="email" />
      <input v-model="form.password" placeholder="Password" type="password" />
      <button type="submit">Update</button>
    </form>
  </div>
</template>

<script>
import { Inertia } from '@inertiajs/inertia';

export default {
  props: {
    user: Object,
  },
  data() {
    return {
      form: {
        name: this.user.name,
        phone: this.user.phone,
        birth_date: this.user.birth_date,
        address: this.user.address,
        email: this.user.email,
        password: '',
      },
    };
  },
  methods: {
    updateUser() {
      Inertia.put(`/users/${this.user.id}`, this.form);
    },
  },
};
</script>
