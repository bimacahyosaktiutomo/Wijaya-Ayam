<script setup>
import { Link, usePage, router } from '@inertiajs/vue3';
import Swal from 'sweetalert2';
const { cartItems, totalPrice } = usePage().props;

const emit = defineEmits([
    'update-qty', 'remove-item'
]);

function updateQtyHandler (id, qty) {
  // Emit the 'add-to-cart' event with the product id
    emit('update-qty', id, qty);
};
function removeItemHandler (id) {
  // Emit the 'add-to-cart' event with the product id
    emit('remove-item', id);
};

function notif (text) {
    Swal.fire({
        title: text,
        icon: 'error',
    })
}
</script>

<template>
            <div class="flex flex-col md:flex-row gap-4">
                <div class="bg-white md:w-2/3 md:rounded-md">

                    <p v-if="cartItems.length === 0" class="text-center py-10">
                        Keranjang mu kosong. <Link :href="route('product')" class="text-amber-500 hover:text-amber-600 font-semibold">Belanja
                            sekarang!</Link>
                    </p>
                    <!-- Loop cart items -->
                    <div v-for="item in cartItems" class="flex w-full md:py-5 md:pr-5 gap-4">
                        <div class="flex-none md:w-1/6 w-1/4 pl-1">
                            <img class="object-contain w-full h-full transition-transform duration-300 transform hover:scale-110"
                                :src="`storage/${item.attributes.gambar}`" :alt=" item.name " />
                        </div>
                        <div class="flex flex-grow flex-col justify-between md:p-0 py-5">
                            <div>
                                <a href="{% url 'tokom:product_details' item.item.item_id %}"
                                    class="line-clamp-2 max-w-full text-sm lg:text-lg">{{ item.name }}</a>
                                <p class="font-bold">Rp. {{ item.price.toLocaleString() }}</p>
                            </div>
                            <div class="flex justify-end text-2xl space-x-6 p-2 text-center">
                                <button @click="removeItemHandler(item.id)"
                                    class="remove-from-cart self-center cursor-pointer fa-regular fa-trash-can hover:text-gray-400"></button>
                                <div class="flex items-center border border-gray-300 rounded-2xl">
                                    <button @click="item.quantity - 1 === 0 ? removeItemHandler(item.id) : updateQtyHandler(item.id, -1)"
                                        class="px-4 py-1 border-x border-x-gray-300 text-center text-lg font-semibold text-gray-800 rounded-l-xl">-</button>
                                    <h4 class="px-3 py-1 text-lg font-semibold text-gray-600">{{ item.quantity }}</h4>
                                    <button @click="updateQtyHandler(item.id, 1)"
                                        class="px-4 py-1 border-x border-x-gray-300 text-center text-lg font-semibold text-gray-800 rounded-r-xl">+</button>
                                </div>
                            </div>
                            <h1 class="text-end text-md md:text-lg font-semibold">
                                Total harga : Rp. {{ (item.price * item.quantity).toLocaleString() }}
                            </h1>
                        </div>
                    </div>
                </div>

                <!-- Cart Summary -->
                <div class="md:w-1/3">
                    <div class="flex w-full flex-col bg-white p-1 md:p-5 md:rounded-md md:top-36 fixed bottom-0 md:sticky space-y-2 md:space-y-4">
                        <h1 class="font-semibold text-xl">Checkout</h1>
                        <hr />
                        <div class="flex lg:flex-row md:flex-col justify-between">
                            <h1 class="font-medium">Total :</h1>
                            <span class="font-semibold">Rp. {{ totalPrice.toLocaleString() }}</span>
                        </div>
                        <hr />
                        <button v-if="cartItems.length === 0" @click="notif('Cart Kosong')" class="box-border w-full bg-amber-500 hover:bg-amber-600 p-3 self-center rounded-xl hover-anim">
                            <span class="text-white font-semibold">Checkout</span>
                        </button>
                        <Link v-else :href="route('checkout')"><button
                                class="box-border w-full bg-amber-500 hover:bg-amber-600 p-3 self-center rounded-xl hover-anim"><span
                                    class="text-white font-semibold">Checkout</span></button></Link>
                    </div>
                </div>
            </div>
</template>