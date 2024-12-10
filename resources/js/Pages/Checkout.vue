<script setup>
import { usePage } from '@inertiajs/vue3';
import Header from '@/Components/Header.vue';

const { cartItems, totalPrice } = usePage().props;
</script>

<template>
    <Header />
    <section class="bg-slate-100 min-h-[100vh]">
        <div class="py-1 md:py-10 lg:px-28 md:px-8 justify-center space-y-4">
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
                                    class="input input-bordered w-full cursor-not-allowed"
                                    v-model="$page.props.auth.user.name"
                                    />
                            </div>
                        </div>

                        <!-- Phone Number -->
                        <div class="space-y-2">
                            <label for="phone" class="font-semibold">Phone Number</label>
                            <div class="form-control border rounded-lg">
                                <input  v-model="$page.props.auth.user.no_telepon" type="text" name="phone" id="phone" placeholder="Phone Number"
                                    class="input input-bordered w-full" required />
                            </div>
                        </div>

                        <!-- Address -->
                        <div class="space-y-2">
                            <label for="address" class="font-semibold">Address</label>
                            <div class="form-control border rounded-lg">
                                <textarea name="address" id="address" placeholder="Street Address, City, Postal Code"
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
                                        class="radio [--chkbg:theme(colors.indigo.500)]" />
                                </label>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary bg-indigo-500 hover:bg-indigo-600 w-full">
                            Place Order
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
                            <p class="text-center py-10">
                                Your cart is empty. <a href="{% url 'tokom:home' %}"
                                    class="text-indigo-500 font-semibold">Shop now!</a>
                            </p>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>
</template>

<style scoped>
.cart-page {
    position: absolute;
    width: 100%;
    height: 100%;
    background-color: #f8f9fa;
    padding: 20px;
    font-family: Arial, sans-serif;
    top: 0;
}

.cart-container {
    margin-top: 90px;
    display: flex;
    flex-direction: column;

}

.shipping-section {
    display: flex;
    gap: 10px;
    margin-bottom: 20px;
}

.address-button {
    background-color: #5a6268;
    color: white;
    padding: 10px 15px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}

.address-box {
    flex: 1;
    background-color: #e9ecef;
    padding: 10px;
    border-radius: 5px;
}

.cart-items {
    margin-bottom: 20px;
}

.cart-item {
    display: flex;
    align-items: center;
    background-color: #ffffff;
    border-radius: 8px;
    padding: 15px;
    margin-bottom: 10px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.item-image {
    width: 80px;
    height: 80px;
    margin-right: 15px;
    border-radius: 8px;
}

.item-details {
    flex: 1;
}

.item-name {
    font-size: 16px;
    font-weight: bold;
}

.item-quantity,
.item-price {
    font-size: 14px;
    margin: 5px 0;
}

.item-actions {
    display: flex;
    flex-direction: column;
    gap: 5px;
}

.quantity-button {
    background-color: #6c757d;
    color: white;
    border: none;
    padding: 5px 10px;
    border-radius: 5px;
    cursor: pointer;
}

.cart-summary {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.checkout-button {
    background-color: #28a745;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}

.total-price {
    font-weight: bold;
}
</style>