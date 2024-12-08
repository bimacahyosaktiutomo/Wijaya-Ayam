<script setup>
import { ref } from 'vue';
import { useForm, Link } from '@inertiajs/vue3';
import Swal from 'sweetalert2';

const { user } = usePage().props;

const form = useForm({
    name: props.user.name,
    email: props.user.email,
    no_telepon: props.user.no_telepon,
});

const submit = () => {
    form.post('/dashboard/store', {
        onSuccess: () => notifSuccess('Produk berhasil ditambah'),
    });
};

</script>


<template>
    <div class="max-w-md mx-auto p-6 bg-white rounded-lg shadow-md">
        <h1 class="text-2xl font-bold mb-4">Edit User {{ props.user.name }}</h1>
        <form @submit.prevent="submit">
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700">Nama Produk</label>
                <input type="text" v-model="form.name"
                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:border-indigo-500 focus:ring-indigo-500"
                    required />
            </div>
            <div class="mb-4">
                <label for="description" class="block text-sm font-medium text-gray-700">Deskripsi</label>
                <input type="email" v-model="form.email"
                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:border-indigo-500 focus:ring-indigo-500"
                    required />
            </div>
            <div class="mb-4">
                <label for="description" class="block text-sm font-medium text-gray-700">Role</label>
            </div>
            <div class="flex justify-between">
                <button type="submit"
                    class="inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Update user
                </button>
                <Link :href="route('dashboard')">
                    <button type="button"
                        class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Batal
                    </button>
                </Link>
            </div>
        </form>
    </div>
</template>

<script>
function notifSuccess (text) {
    Swal.fire({
        title: text,
        icon: 'success',
    })
}
</script>