<template>
  <div class="flex items-center justify-center min-h-screen bg-gray-100">
    <div class="bg-white p-8 rounded-2xl shadow-lg w-full max-w-md">
      <h1 class="text-2xl font-bold text-center mb-6 text-gray-700">Импорт пользователей из CSV</h1>
      <form @submit.prevent="handleCsvUpload" enctype="multipart/form-data" class="space-y-4">
        <label class="block">
          <input
              type="file"
              @change="onFileChange"
              accept=".csv"
              class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none"
          />
        </label>

        <button
            type="submit"
            :disabled="!file"
            class="w-full bg-blue-500 text-white py-2 px-4 rounded-xl hover:bg-blue-600 disabled:opacity-50"
        >
          Импортировать
        </button>
      </form>
    </div>
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
