<script setup>
import DashboardLayout from '@/Layouts/DashboardLayout.vue';
import { usePage, Link, router, Head } from '@inertiajs/vue3';
const { products } = usePage().props;
</script>

<template>
    <DashboardLayout sideBarActive="product">
        <div class="bg-white p-1 rounded-sm">
            <div class="flex justify-between px-8 my-4">
                <h1 class="font-semibold text-xl">Product</h1>
                <Link :href="route('dashboard.product.add')"
                    class="bg-green-400 p-2 rounded-lg font-semibold hover:bg-green-500 hover-anim text-white">
                    Tambah
                </Link>
            </div>
    
            <table class="min-w-full border border-gray-300">
                <thead class="bg-gray-200">
                    <tr>
                        <th scope="col" class="px-4 py-2 border-b">ID</th>
                        <th scope="col" class="px-4 py-2 border-b">Nama</th>
                        <th scope="col" class="px-4 py-2 border-b">Deskripsi</th>
                        <th scope="col" class="px-4 py-2 border-b">Harga</th>
                        <th scope="col" class="px-4 py-2 border-b">Stok</th>
                        <th scope="col" class="px-4 py-2 border-b">Gambar</th>
                        <th scope="col" class="px-4 py-2 border-b text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-if="products.length === 0">
                        <td colspan="9" class="px-4 py-2 text-center">Tidak ada produk</td>
                    </tr>
                    <tr v-for="item in products" :key="item.id_produk" class="text-center bg-white border-b">
                        <th scope="row" class="px-4 py-2 font-normal">{{ item.id_produk }}</th>
                        <td class="px-4 py-2">{{ item.nama_produk }}</td>
                        <td class="px-4 py-2">{{ item.deskripsi }}</td>
                        <td class="px-4 py-2">{{ item.harga.toLocaleString() }}</td>
                        <td class="px-4 py-2">{{ item.stok }}</td>
                        <td class="" v-if="item.gambar">
                            <div class="w-full flex justify-center">
                                <img :src="`/storage/${item.gambar}`" alt="Product Image" class="w-20 h-auto">
                            </div>
                        </td>
                        <td class="flex justify-center px-4 py-2">
                            <Link :href="route('dashboard.product.edit', { id: item.id_produk })">
                                <button class="bg-blue-500 text-white px-2 py-1 rounded mr-2 hover:bg-blue-600 hover-anim">
                                    <i class="fa-regular fa-pen-to-square"></i></button>
                            </Link>
                            <button @click="confirmDelete(item.id_produk, item.nama_produk)" 
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

function confirmDelete(itemsId, itemName) {
    Swal.fire({
        title: `Produk akan dihapus`,
        text: `Nama : ${itemName} \n ID : ${itemsId}`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '##e5e7eb',
        confirmButtonText: 'Ya, Hapus!'
    }).then((result) => {
        if (result.isConfirmed) {
            // Pake Inertia
            router.delete(`/dashboard/product/delete/${itemsId}`, {
                onSuccess: () => {
                    Swal.fire(
                        'Deleted!',
                        'Product berhasil dihapus.',
                        'success'
                    );
                    // router.visit('dashboard/product');
                    router.visit(route('dashboard.product'));
                },
                onError: () => {
                    Swal.fire(
                        'Error!',
                        'Gagal menghapus Product.',
                        'error'
                    );
                }
            });

            //Inertia
            // Inertia.delete(`/dashboard/delete/${itemsId}`, {
            //     onSuccess: () => {
            //         Swal.fire(
            //             'Deleted!',
            //             'Item berhasil dihapus.',
            //             'success'
            //         );
            //         // Optionally redirect after the deletion
            //         Inertia.visit('/dashboard/product');
            //     },
            //     onError: () => {
            //         Swal.fire(
            //             'Error!',
            //             'Gagal menghapus item.',
            //             'error'
            //         );
            //     }
            // });

            // Named route
            // router.delete(route(`dashboard.product.delete`, {id :itemsId}) , {
            //     onSuccess: () => {
            //         Swal.fire(
            //             'Deleted!',
            //             'Product berhasil dihapus.',
            //             'success'
            //         );
            //         router.visit('dashboard.product');
            //     },
            //     onError: () => {
            //         Swal.fire(
            //             'Error!',
            //             'Gagal menghapus Product.',
            //             'error'
            //         );
            //     }
            // });

            // Pake Axios
            // axios.delete(`/dashboard/delete/${itemsId}`, {
            // }).then(response => {
            //     if (response.status === 200) {
            //         Swal.fire(
            //             'Deleted!',
            //             'Item berhasil dihapus.',
            //             'success'
            //         ).then(() => {
            //             router.visit('dashboard.product');
            //         });
            //     } else {
            //         Swal.fire(
            //             'Error!',
            //             'Gagal menghapus item.',
            //             'error'
            //         );
            //     }
            // })
        }
    });
}
</script>