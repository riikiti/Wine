<template>
  <div>
    <h1>Импорт пользователей из CSV</h1>
    <form @submit.prevent="handleCsvUpload" enctype="multipart/form-data">
      <input type="file" @change="onFileChange" accept=".csv" />
      <button type="submit" :disabled="!file">Импортировать</button>
    </form>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';

const file = ref(null);

function onFileChange(e) {
  file.value = e.target.files[0];
}

async function handleCsvUpload() {
  if (!file.value) {
    alert('Выберите файл для загрузки.');
    return;
  }

  const formData = new FormData();
  formData.append('file', file.value);

  try {
    await router.post('/users/import-csv', formData);
    alert('Файл успешно загружен и обработан!');
  } catch (error) {
    alert('Ошибка при загрузке файла.');
  }
}
</script>
