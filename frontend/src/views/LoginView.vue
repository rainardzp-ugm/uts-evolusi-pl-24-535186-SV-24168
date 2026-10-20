<template>
  <div style="max-width: 400px; margin: 50px auto; padding: 20px; border: 1px solid #ccc; border-radius: 8px;">
    <h2>Login Sistem Buku</h2>
    <div v-if="errorMessage" style="color: red; margin-bottom: 10px;">{{ errorMessage }}</div>
    <form @submit.prevent="handleLogin">
      <div style="margin-bottom: 10px;">
        <label>Email:</label>
        <input type="email" v-model="email" required style="width: 100%; padding: 8px; box-sizing: border-box;" />
      </div>
      <div style="margin-bottom: 15px;">
        <label>Password:</label>
        <input type="password" v-model="password" required style="width: 100%; padding: 8px; box-sizing: border-box;" />
      </div>
      <button type="submit" style="width: 100%; padding: 10px; background-color: #4CAF50; color: white; border: none; cursor: pointer;">
        Login
      </button>
    </form>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import api from '../services/api';

const email = ref('');
const password = ref('');
const errorMessage = ref('');
const router = useRouter();

const handleLogin = async () => {
  errorMessage.value = '';
  try {
    const response = await api.post('/login', {
      email: email.value,
      password: password.value
    });

    // Simpan token ke localStorage
    localStorage.setItem('token', response.data.access_token);
    
    // Redirect ke halaman kelola buku
    router.push('/books');
  } catch (error: any) {
    if (error.response && error.response.status === 401) {
      errorMessage.value = 'Email atau password salah!';
    } else {
      errorMessage.value = 'Terjadi kesalahan pada server.';
    }
  }
};
</script>