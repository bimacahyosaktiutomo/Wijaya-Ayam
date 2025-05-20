<script setup>
import Header from '@/Components/Header.vue';
import Footer from '@/Components/Footer.vue';
import CartView from '@/Components/CartView.vue';
import { Link, usePage, router } from '@inertiajs/vue3';
import { ref } from 'vue';

const cartKey = ref(0); // Buat reload/re-render header

function updateQty(itemId, quantity) {
    router.patch(`/cart/updateQty/${itemId}`, {qty:quantity}, {
        onFinish: () => {
            // router.visit(route('cart'), { preserveScroll: true });
            cartKey.value++;
        },
        preserveScroll: true, // nyimpan scroll pas update data, bukan buat visit atau get ke halaman sendiri / halaman lain
    })
}

function removeItem(itemId) {
    router.post(`/cart/remove/${itemId}`, {}, {
        onFinish: () => {
            // router.visit(route('cart'), { preserveScroll: true });
            cartKey.value--; // biar heeader nya reload pas proses selesai
        },
        preserveScroll: true, // nyimpan scroll pas update data, bukan buat visit atau get ke halaman sendiri / halaman lain
    });
}
</script>

<template>
    <Header :key="cartKey" activePage="cart" />
    <section class="bg-slate-100 min-h-[80vh] py-20">
        <div class="py-1 md:py-10 lg:px-28 md:px-8 justify-center space-y-4">
            <h1 class="font-semibold text-2xl text-center">Cart</h1>
            <!-- fungsinya gak pake parameter karena parameternya dari childnya (CardView) cek disitu -->
            <CartView 
                :key="cartKey"
                @update-qty="updateQty"
                @remove-item="removeItem"
                /> 
        </div>
    </section>
    <Footer class="hidden md:block" />
</template>