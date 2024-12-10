<script setup>
import Header from '@/Components/Header.vue';
import Footer from '@/Components/Footer.vue';
import { Link, usePage, router } from '@inertiajs/vue3';

const { cartItems, totalPrice } = usePage().props;
</script>

<template>
    <Header/>
    <section class="bg-slate-100 min-h-[80vh] py-20">
        <div class="py-1 md:py-10 lg:px-28 md:px-8 justify-center space-y-4">
            <h1 class="font-semibold text-2xl text-center">Cart</h1>
            <div class="flex flex-col md:flex-row gap-4">
                <div class="bg-white md:w-2/3 md:rounded-md">

                    <p v-if="cartItems.length === 0" class="text-center py-10">
                        Your cart is empty. <Link :href="route('product')" class="text-amber-500 hover:text-amber-600 font-semibold">Shop
                            now!</Link>
                    </p>
                    <!-- Loop through cart items -->
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
                                <button @click="removeItem(item.id)"
                                    class="remove-from-cart self-center cursor-pointer fa-regular fa-trash-can hover:text-gray-400"></button>
                                <div class="flex items-center border border-gray-300 rounded-2xl">
                                    <button @click="item.quantity - 1 === 0 ? removeItem(item.id) : updateQty(item.id, -1)"
                                        class="px-4 py-1 border-x border-x-gray-300 text-center text-lg font-semibold text-gray-800 rounded-l-xl">-</button>
                                    <h4 class="px-3 py-1 text-lg font-semibold text-gray-600">{{ item.quantity }}</h4>
                                    <!-- {% if item.quantity >= item.item.stock %} -->
                                    <!-- <a href="#"
                                        class="px-4 py-1 border-x border-x-gray-300 text-center text-lg font-semibold text-gray-800 rounded-r-xl cursor-not-allowed opacity-50"
                                        aria-disabled="true">+</a> -->
                                    <!-- {% else %} -->
                                    <button @click="updateQty(item.id, 1)"
                                        class="px-4 py-1 border-x border-x-gray-300 text-center text-lg font-semibold text-gray-800 rounded-r-xl">+</button>
                                    <!-- {% endif %} -->

                                    <!-- {% comment %} <a
                                        href="{% url 'tokom:cart_update' item.item.item_id %}?action=increase"
                                        class="px-4 py-1 border-x border-x-gray-300 text-center text-lg font-semibold text-gray-800 rounded-r-xl">+</a>
                                    {% endcomment %} -->
                                </div>
                            </div>
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
                        <Link :href="route('checkout')"><button
                                class="box-border w-full bg-amber-500 hover:bg-amber-600 p-3 self-center rounded-xl hover-anim"><span
                                    class="text-white font-semibold">Checkout</span></button></Link>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <Footer class="hidden md:block" />
</template>

<script>
function updateQty(itemId, quantity) {
    router.patch(`/cart/updateQty/${itemId}`, {qty:quantity}, {
        onFinish: () => {
            router.visit(route('cart'), { preserveScroll: true });
        }
    })
}

function removeItem(itemId) {
    router.post(`/cart/remove/${itemId}`, {}, {
        onFinish: () => {
            router.visit(route('cart'), { preserveScroll: true });
        }
    });
}
</script>