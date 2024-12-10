<script setup>
import { usePage, Link, router} from '@inertiajs/vue3';
import Header from '@/Components/Header.vue';
import Swal from 'sweetalert2';
import moment from "moment";
import 'moment/locale/id'
const { order, items } = usePage().props;

moment.locale('id');

function updateStatus (idPemesanan) {
    router.patch(route('update.status', idPemesanan), { status_pemesanan: 'Selesai' }, {
        onSuccess: () => {
            Swal.fire({
                title: 'Pesanan Diterima!',
                icon: 'success',
            }).then(
                router.visit(`/order/${idPemesanan}/details`, {preserveScroll: true})
            );
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
    <Header/>
    <section class="py-24 md:px-4 bg-slate-100 min-h-[100vh]">
        <div class="flex flex-col bg-white space-y-3 p-4 shadow-sm border rounded-lg">
            <h1 class="font-bold text-xl">Order Information</h1>
            <hr>
            <div class="flex flex-col px-4 text-sm md:text-base">
                <h1>Order Id : {{ order.id_pemesanan }}</h1>
                <p>Status: {{ order.status_pemesanan }}</p>
                <p>Address: {{ order.alamat_pengiriman }}</p>
                <p>Tanggal dipesan: {{ moment(order.created_at).format("LLLL") }}</p>
                <p class="font-semibold text-lg">Total Harga: Rp. {{ order.total_harga.toLocaleString() }}</p>
                <button v-if="order.status_pemesanan == 'Diantar' && order.id_pelanggan == $page.props.auth.user.id_pengguna" @click="updateStatus(order.id_pemesanan)" class="self-end w-fit mt-2 bg-green-400 hover:bg-green-500 transition p-3 rounded-lg font-semibold text-white text-sm md:text-base">Terima Pesanan</button>
            </div>
            <hr>
            <div class="self-center w-full md:w-3/4">
                <h1 class="font-bold text-xl">Detail Item</h1>
                <div class="space-y-3 p-3 border rounded-lg shadow-lg">
                    <div v-for="item in items" class="flex justify-between space-y-2">
                        <div class="flex space-x-3">
                            <div class="">
                                <h1 class="line-clamp-2 text-base xs:text-xl font-bold">{{ item.nama_produk }}</h1>
                                <p class="font-light text-xs xs:text-base">{{ item.kuantitas }} x Rp. {{ item.harga_satuan.toLocaleString() }}</p>
                            </div>
                        </div>
                        <div>
                            <p class="text-xs">Total Harga</p>
                            <h1 class="font-semibold text-sm">Rp. {{ (item.kuantitas * item.harga_satuan).toLocaleString() }}</h1>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>