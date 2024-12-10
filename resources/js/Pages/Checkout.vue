<script setup>
import { usePage, Link, useForm, router} from '@inertiajs/vue3';
import Header from '@/Components/Header.vue';

const { cartItems, totalPrice } = usePage().props;

const form = useForm({
    id_pelanggan: usePage().props.auth.user.id_pengguna,
    nama_pelanggan: usePage().props.auth.user.name,
    no_telepon: usePage().props.auth.user.no_telepon,
    status_pemesanan: 'Diantar',
    total_harga: totalPrice,
    alamat_pengiriman: ''
});

const submit = () => {
    form.post('/checkout/order', {
        onSuccess: () => {
            notifSuccess('Pesanan akan diproses');
        }
    });
};


</script>

<template>
    <Header />
    <section class="bg-slate-100 min-h-[100vh]">
        <div class="py-6 md:py-10 lg:px-28 md:px-8 justify-center space-y-4">
            <!-- Breadcrumbs -->
            <div class="breadcrumbs text-sm">
                <ul>
                    <li>
                        <Link :href="route('cart')">
                            <span class="inline-flex items-center gap-2">
                                <i class="fa-regular fa-square-check"></i>
                                Cart
                            </span>
                        </Link>
                    </li>
                    <li>
                        <span class="inline-flex items-center gap-2">
                            <i class="fa-solid fa-money-bill"></i>
                            Checkout
                        </span>
                    </li>
                </ul>
            </div>

            <h1 class="font-semibold text-2xl text-center">Checkout</h1>
            <div class="flex flex-col md:flex-row gap-4">

                <!-- Checkout Form -->
                <div class="bg-white md:w-2/3 md:rounded-md p-4 md:p-12">
                    <form @submit.prevent="submit" class="space-y-4">
                        <!-- Worker Name -->
                        <div class="space-y-2">
                            <label for="name" class="font-semibold">Fullname</label>
                            <div class="form-control border rounded-lg">
                                <input type="text" name="name" id="name"
                                    placeholder="Fullname"
                                    class="input input-bordered w-full"
                                    v-model="form.nama_pelanggan"
                                    />
                            </div>
                        </div>

                        <!-- Phone Number -->
                        <div class="space-y-2">
                            <label for="phone" class="font-semibold">Phone Number</label>
                            <div class="form-control border rounded-lg">
                                <input  v-model="form.no_telepon" type="text" name="phone" id="phone" placeholder="Phone Number"
                                    class="input input-bordered w-full" required />
                            </div>
                        </div>

                        <!-- Address -->
                        <div class="space-y-2">
                            <label for="address" class="font-semibold">Address</label>
                            <div class="form-control border rounded-lg">
                                <textarea v-model="form.alamat_pengiriman" name="address" id="address" placeholder="Street Address, City, Postal Code"
                                    class="textarea textarea-bordered w-full resize-none "
                                    rows="3"
                                    required></textarea>
                            </div>
                        </div>

                        <!-- Payment Section -->
                        <div class="space-y-2">
                            <label for="" class="font-semibold">Payment</label>
                            <div class="form-control border rounded-lg w-1/3 p-1 bg-gray-100 cursor-not-allowed">
                                <label class="label cursor-pointer">
                                    <span class="label-text">Cash on Delivery (COD)</span>
                                    <input type="radio" name="payment" value="cod" checked
                                        class="radio [--chkbg:theme(colors.amber.500)]" />
                                </label>
                            </div>
                        </div>

                        <button v-if="cartItems.length === 0" @click="notif('Cart Kosong')" class="btn bg-amber-400 hover:bg-amber-500 w-full">
                            Pesan
                        </button>
                        <button v-else type="submit" class="btn bg-amber-400 hover:bg-amber-500 w-full">
                            Pesan
                        </button>
                    </form>
                </div>

                <!-- Cart Summary -->
                <div class="md:w-1/3">
                    <div class="md:w-full">
                        <div class="flex w-full flex-col bg-white md:rounded-md p-1 md:p-5 md:top-0 space-y-2 mx-auto">
                            <h1 class="font-semibold text-xl">Cart Detail</h1>
                            <hr />
                            <div v-for="item in cartItems" class="flex w-full md:pl-1 md:py-5 md:pr-5 gap-4">
                                <a href="" class="flex-none md:w-1/5 w-1/4 pl-1 h-full self-center">
                                    <img class="object-contain w-full h-16 transition-transform duration-300 transform hover:scale-110"
                                        :src="`storage/${item.attributes.gambar}`" alt="{{ item.name }}" />
                                </a>
                                <div class="flex flex-grow flex-col md:p-0 py-5">
                                    <a href="" class="line-clamp-2 w-fit text-sm">{{ item.name }}</a>
                                    <p class="block font-thin text-sm">Quantity: {{ item.quantity }}</p>
                                    <p class="block font-bold text-sm">Rp. {{ (item.price * item.quantity).toLocaleString() }}</p>
                                </div>
                            </div>
                            <hr />
                            <div class="flex lg:flex-row md:flex-col justify-between">
                                <h1 class="font-medium">Total :</h1>
                                <span class="font-semibold">Rp. {{ totalPrice.toLocaleString() }}</span>
                            </div>
                            <hr />
                            <p v-if="cartItems.length === 0" class="text-center py-10">
                                Cart Kosong <Link :href="route('product')"
                                    class="text-amber-500 font-semibold">Belanja sekarang!</Link>
                            </p>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>
</template>

<script>
import Swal from 'sweetalert2';

function notif (text) {
    Swal.fire({
        title: text,
        icon: 'error',
    })
}

function notifSuccess(text) {
    Swal.fire({
        title: text,
        icon: 'success',
    })
}
</script>