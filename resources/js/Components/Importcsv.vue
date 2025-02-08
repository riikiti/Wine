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
import { useForm } from '@inertiajs/vue3';

const file = ref(null);
const form = useForm({ file: null });

function onFileChange(e) {
  file.value = e.target.files[0];
  form.file = file.value;
}

async function handleCsvUpload() {
  if (!form.file) {
    alert('Выберите файл для загрузки.');
    return;
  }

  try {
    await form.post('/import/import-csv');
    alert('Файл успешно загружен и обработан!');
  } catch (error) {
    alert('Ошибка при загрузке файла.');
  }
}
</script>
