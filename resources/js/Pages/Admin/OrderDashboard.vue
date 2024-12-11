<script setup>
import DashboardLayout from '@/Layouts/DashboardLayout.vue';
import { usePage, Link, router } from '@inertiajs/vue3';
import Swal from 'sweetalert2';
const { orders } = usePage().props;

function getDetails(orderId) {
    router.get(`/dashboard/order/${orderId}/details/`);
}

function updateStatus (idPemesanan, newStatus) {
    router.patch(route('dashboard.order.status', idPemesanan), { status_pemesanan: newStatus }, {
        onSuccess: () => {
            Swal.fire({
                title: 'Status berhasil diperbarui!',
                icon: 'success',
            });
            router.visit(route('dashboard.order'), {preserveScroll:true})
        },
        onError: () => {
            Swal.fire({
                title: 'Status gagal diperbarui!',
                icon: 'error',
            });
        }
    });
};
</script>

<template>
    <DashboardLayout sideBarActive="order">
        <div class="bg-white p-1 rounded-sm">
            <div class="flex justify-between px-8 my-4">
                <h1 class="font-semibold text-xl">Order</h1>
            </div>

            <table class="min-w-full border border-gray-300">
                <thead class="bg-gray-200">
                    <tr>
                        <th scope="col" class="px-4 py-2 border-b">ID</th>
                        <th scope="col" class="px-4 py-2 border-b">ID Pelanggan</th>
                        <th scope="col" class="px-4 py-2 border-b">Nama</th>
                        <th scope="col" class="px-4 py-2 border-b">No Hp</th>
                        <th scope="col" class="px-4 py-2 border-b">Status</th>
                        <th scope="col" class="px-4 py-2 border-b">Total</th>
                        <th scope="col" class="px-4 py-2 border-b">Alamat</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-if="orders.length === 0">
                        <td colspan="9" class="px-4 py-2 text-center">Tidak ada Order</td>
                    </tr>
                    <tr v-for="order in orders"
                        :key="order.id_pemesanan"
                        class="bg-white text-center border-b cursor-pointer hover:bg-slate-50 transition">
                        <th @click="getDetails(order.id_pemesanan)" scope="row" class="px-4 py-2 font-normal">{{ order.id_pemesanan }}</th>
                        <td @click="getDetails(order.id_pemesanan)" class="px-4 py-2">{{ order.id_pelanggan }}</td>
                        <td @click="getDetails(order.id_pemesanan)" class="px-4 py-2">{{ order.nama_pelanggan }}</td>
                        <td @click="getDetails(order.id_pemesanan)" class="px-4 py-2">{{ order.no_telepon }}</td>
                        <td class="px-4 py-2">
                            <select
                                :class="order.status_pemesanan === 'Selesai'
                                    ? 'bg-green-100 text-green-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-green-900 dark:text-green-300 cursor-pointer'
                                    : 'bg-yellow-100 text-yellow-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-yellow-900 dark:text-yellow-300 cursor-pointer'"
                                v-model="order.status_pemesanan"
                                @change="updateStatus(order.id_pemesanan, order.status_pemesanan)">
                                <option value="Diantar">Diantar</option>
                                <option class="bg-green-100 text-green-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-green-900 dark:text-green-300'" value="Selesai">Selesai</option>
                            </select>
                        </td>
                        <td @click="getDetails(order.id_pemesanan)" class="px-4 py-2">{{ order.total_harga }}</td>
                        <td @click="getDetails(order.id_pemesanan)" class="px-4 py-2 line-clamp-1">{{ order.alamat_pengiriman }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </DashboardLayout>
</template>