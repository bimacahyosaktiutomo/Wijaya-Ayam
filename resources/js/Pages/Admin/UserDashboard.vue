<script setup>
import DashboardLayout from '@/Layouts/DashboardLayout.vue';
import { usePage, Link, router, Head } from '@inertiajs/vue3';
const { users } = usePage().props;
</script>

<template>
    <DashboardLayout>
        <div class="bg-white p-1 rounded-sm">
            <div class="flex justify-between px-8 my-4">
                <h1 class="font-semibold text-xl">User</h1>
            </div>
    
            <table class="min-w-full border border-gray-300">
                <thead class="bg-gray-200">
                    <tr>
                        <th scope="col" class="px-4 py-2 border-b">ID</th>
                        <th scope="col" class="px-4 py-2 border-b">Nama</th>
                        <th scope="col" class="px-4 py-2 border-b">Email</th>
                        <th scope="col" class="px-4 py-2 border-b">No Telepon</th>
                        <th scope="col" class="px-4 py-2 border-b text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-if="users.length === 0">
                        <td colspan="9" class="px-4 py-2 text-center">Tidak ada user</td>
                    </tr>
                    <tr v-for="user in users" :key="user.id_pengguna" class="bg-white border-b">
                        <th scope="row" class="px-4 py-2 font-normal">{{ user.id_pengguna }}</th>
                        <td class="px-4 py-2">{{ user.name }}</td>
                        <td class="px-4 py-2">{{ user.email }}</td>
                        <td class="px-4 py-2">{{ user.no_telepon }}</td>
                        <td class="flex justify-center px-4 py-2">
                            <Link :href="route('dashboard.user.edit', { id: user.id_pengguna })">
                                <button class="bg-blue-500 text-white px-2 py-1 rounded mr-2 hover:bg-blue-600 hover-anim">
                                    <i class="fa-regular fa-pen-to-square"></i></button>
                            </Link>
                            <button @click="confirmDelete(user.name, user.id_pengguna)" 
                                class="bg-red-500 text-white px-2 py-1 rounded hover:bg-red-600 hover-anim">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </DashboardLayout>
</template>

<script>
import Swal from 'sweetalert2';

function confirmDelete(userName, userId) {
    if (userId != 1) {
        Swal.fire({
            title: `User akan dihapus`,
            text: `Nama : ${userName} \n ID : ${userId}`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '##e5e7eb',
            confirmButtonText: 'Ya, Hapus!'
        }).then((result) => {
            if (result.isConfirmed) {
                // Pake Inertia
                router.delete(`/dashboard/user/delete/${userId}`, {
                    onSuccess: () => {
                        Swal.fire(
                            'Deleted!',
                            'User berhasil dihapus.',
                            'success'
                        );
                        router.visit('dashboard.user');
                    },
                    onError: () => {
                        Swal.fire(
                            'Error!',
                            'Gagal menghapus User.',
                            'error'
                        );
                    }
                });
            }
        });
    }else{
        Swal.fire({
            title: `User tidak bisa dihapus`,
            text: `Nama : ${userName} \n ID : ${userId}`,
            icon: 'warning',
            iconColor: '#dc2626',
            confirmButtonColor: '#dc2626',
            confirmButtonText: 'Kembali'
        })
    }
}
</script>