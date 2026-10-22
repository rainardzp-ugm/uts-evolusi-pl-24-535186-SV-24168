<template>
  <div style="max-width: 800px; margin: 30px auto; padding: 20px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
      <h2>Manajemen Data Buku</h2>
      <button @click="handleLogout" style="padding: 8px 16px; background-color: #f44336; color: white; border: none; cursor: pointer;">
        Logout
      </button>
    </div>

    <!-- Form Tambah / Edit -->
    <div style="background: #f9f9f9; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
      <h3>{{ isEditing ? 'Edit Buku' : 'Tambah Buku Baru' }}</h3>
      <form @submit.prevent="handleSubmit">
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 10px;">
          <input type="text" v-model="form.title" placeholder="Judul Buku" required style="padding: 8px;" />
          <input type="text" v-model="form.author" placeholder="Penulis" required style="padding: 8px;" />
          <input type="number" v-model="form.year" placeholder="Tahun Terbit" required style="padding: 8px;" />
          <input type="number" v-model="form.stock" placeholder="Stok" required style="padding: 8px;" />
        </div>
        <button type="submit" style="padding: 8px 16px; background-color: #2196F3; color: white; border: none; cursor: pointer;">
          {{ isEditing ? 'Perbarui' : 'Simpan' }}
        </button>
        <button v-if="isEditing" type="button" @click="resetForm" style="margin-left: 10px; padding: 8px 16px; background-color: #9e9e9e; color: white; border: none; cursor: pointer;">
          Batal
        </button>
      </form>
    </div>

    <!-- Tabel Data Buku -->
    <table border="1" cellpadding="10" cellspacing="0" style="width: 100%; border-collapse: collapse;">
      <thead>
        <tr style="background-color: #eee;">
          <th>No</th>
          <th>Judul</th>
          <th>Penulis</th>
          <th>Tahun</th>
          <th>Stok</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        <tr v-if="books.length === 0">
          <td colspan="6" style="text-align: center;">Belum ada data buku.</td>
        </tr>
        <tr v-for="(book, index) in books" :key="book.id">
          <td>{{ index + 1 }}</td>
          <td>{{ book.title }}</td>
          <td>{{ book.author }}</td>
          <td>{{ book.year }}</td>
          <td>{{ book.stock }}</td>
          <td>
            <button @click="editBook(book)" style="margin-right: 5px; background: #ff9800; color: white; border: none; padding: 5px 10px; cursor: pointer;">
              Edit
            </button>
            <button @click="deleteBook(book.id)" style="background: #f44336; color: white; border: none; padding: 5px 10px; cursor: pointer;">
              Hapus
            </button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import api from '../services/api';

interface Book {
  id: number;
  title: string;
  author: string;
  year: number;
  stock: number;
}

const books = ref<Book[]>([]);
const isEditing = ref(false);
const editingId = ref<number | null>(null);
const router = useRouter();

const form = ref({
  title: '',
  author: '',
  year: '' as number | string,
  stock: '' as number | string
});

const fetchBooks = async () => {
  try {
    const response = await api.get('/books');
    books.value = response.data.data;
  } catch (error: any) {
    if (error.response && error.response.status === 401) {
      router.push('/');
    }
  }
};

const handleSubmit = async () => {
  try {
    if (isEditing.value && editingId.value) {
      await api.put(`/books/${editingId.value}`, form.value);
    } else {
      await api.post('/books', form.value);
    }
    resetForm();
    fetchBooks();
  } catch (error) {
    alert('Gagal menyimpan data buku!');
  }
};

const editBook = (book: Book) => {
  isEditing.value = true;
  editingId.value = book.id;
  form.value = {
    title: book.title,
    author: book.author,
    year: book.year,
    stock: book.stock
  };
};

const deleteBook = async (id: number) => {
  if (confirm('Yakin ingin menghapus buku ini?')) {
    try {
      await api.delete(`/books/${id}`);
      fetchBooks();
    } catch (error) {
      alert('Gagal menghapus buku!');
    }
  }
};

const resetForm = () => {
  isEditing.value = false;
  editingId.value = null;
  form.value = { title: '', author: '', year: '', stock: '' };
};

const handleLogout = async () => {
  try {
    await api.post('/logout');
  } catch (e) {
    // Abaikan jika token expired
  } finally {
    localStorage.removeItem('token');
    router.push('/');
  }
};

onMounted(() => {
  fetchBooks();
});
</script>