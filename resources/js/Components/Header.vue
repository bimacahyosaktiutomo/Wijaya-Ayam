<script setup>
import { Link, router, usePage } from '@inertiajs/vue3';
import { defineProps } from 'vue';

const { cartInfo } = usePage().props;
const isAuthenticated = usePage().props.auth.user !== null;

const cartItems = isAuthenticated ? cartInfo.cartItems : [];
const totalCartItems = isAuthenticated ? cartInfo.totalCartItems : 0;
const totalPrice = isAuthenticated ? cartInfo.totalPrice : 0;

defineProps({
    activePage: String,
});
</script>

<!-- Alternativ, bikin propsnya di script laen soalnya gak bisa di setup -->
<script>
import Swal from 'sweetalert2';

function notif (text) {
Swal.fire({
    title: text,
    icon: 'error',
})
}
// export default {
//     name: 'Header',
//     props: {
//         canLogin: Boolean,
//         canRegister: Boolean,
//     },
// };
</script>

<template>
  <header class="header">
    <nav class="navbar">
      <div class="w-24">
        <img src="../assets/LogoWijayaAyam2.png" alt="">
      </div>
      <ul class="nav-links">
        <li>
          <Link :href="route('home')">
          <h1 :class="{'text-amber-400': activePage === 'home',}">Home</h1>
          </Link>
        </li>
        <li>
          <Link :href="route('product')">
          <h1 :class="{'text-amber-400': activePage === 'product',}">Product</h1>
          </Link>
        </li>
        <li>
          <Link :href="route('about')">
          <h1 :class="{'text-amber-400': activePage === 'about',}">About</h1>
          </Link>
        </li>
      </ul>
      <div class="flex items-center space-x-4">
        <li class="cart dropdown dropdown-hover dropdown-end">
          <Link :href="route('cart')">
          <a class="flex hover:text-amber-400 hover-anim">
            <i class="text-xl fa-solid fa-cart-shopping" :class="{'text-amber-400': activePage === 'cart',}"></i>
            <p v-if="$page.props.auth.user" class="text-xs">{{ totalCartItems }}</p>
          </a>
          </Link>

          <!-- Cart Content -->
          <div :class="{
              'dropdown-content menu relative w-[30rem] p-2 space-y-2 self-center bg-white border rounded-lg shadow-2xl': isAuthenticated,
              'hidden': !isAuthenticated
            }">
            <div class="flex text-lg font-semibold">
              <h1>Keranjang</h1>
              <span>({{ totalCartItems }})</span>
            </div>
            <hr />

            <!-- Cart Items -->
            <div class="overflow-y-auto h-60">
              <!-- Items -->
              <div v-for="item in cartItems" class="flex space-x-4">
                <div class="flex flex-none justify-center w-1/4">
                  <img class="w-20 h-20 object-contain" :src="`storage/${item.attributes.gambar}`"
                    alt="{{ item.name }}" />
                </div>
                <div class="flex flex-col text-sm">
                  <h1 class="line-clamp-1">{{ item.name }}</h1>
                  <h1 class="font-semibold">Rp. {{ item.price.toLocaleString() }}</h1>
                  <p>Quantity: {{ item.quantity }}</p>
                </div>
              </div>
              <p v-if="cartItems.length === 0" class="text-center text-sm text-gray-500">Your cart is empty.</p>
            </div>

            <hr />
            <div class="flex justify-between font-semibold">
              <h1>Total:</h1>
              <h1>Rp. {{ totalPrice.toLocaleString() }}</h1>
            </div>

            <button v-if="cartItems.length === 0" @click="notif('Cart Kosong')"
              class="btn bg-amber-500 hover:bg-amber-600 text-white w-full text-center mt-2">Checkout</button>
            <Link v-else :href="route('checkout')"
              class="btn bg-amber-500 hover:bg-amber-600 text-white w-full text-center mt-2">Checkout</Link>
          </div>
        </li>
        <li v-if="$page.props.auth && $page.props.auth.user" class="dropdown dropdown-hover dropdown-end">
          <div class="flex space-x-1 font-semibold hover:text-amber-400 hover-anim">
            <button class="text-xl"><i class="fa-solid fa-user"></i></button>
            <h1 class="self-center text-lg">{{ $page.props.auth.user.name }}</h1>
          </div>
          <ul class="dropdown-content menu bg-base-100 rounded-box z-[1] w-40 p-2 shadow">
            <li v-if="$page.props.auth.role === 'admin'">
              <Link :href="route('dashboard.product')">
              Dashboard
              </Link>
            </li>
            <li>
              <Link :href="route('history')">
              Order History
              </Link>
            </li>
            <li class="md:hidden">
              <Link :href="route('home')">
              Home
              </Link>
            </li>
            <li class="md:hidden">
              <Link :href="route('product')">
              Product
              </Link>
            </li>
            <li class="md:hidden">
              <Link :href="route('about')">
              About
              </Link>
            </li>
            <li>
              <Link :href="route('logout')" method="post" as="button" class="text-red-500">
              Logout
              </Link>
            </li>
          </ul>
        </li>
        <li v-else class="dropdown dropdown-hover dropdown-end">
          <button class="text-xl"><i class="fa-solid fa-user"></i></button>
          <ul class="dropdown-content menu bg-base-100 rounded-box z-[1] w-40 p-2 shadow">
            <li>
              <Link v-if="$page.props.canLogin" :href="route('login')">
              Login
              </Link>
            </li>
            <li class="">
              <Link :href="route('home')">
              Home
              </Link>
            </li>
            <li class="">
              <Link :href="route('product')">
              Product
              </Link>
            </li>
            <li class="">
              <Link :href="route('about')">
              About
              </Link>
            </li>
            <li>
              <Link v-if="$page.props.canRegister" :href="route('register')">
              Register
              </Link>
            </li>
          </ul>
        </li>
      </div>
    </nav>
  </header>
</template>

<style scoped>
.header {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  background-color: transparent;
  z-index: 1000;
  padding: 10px 0;
}

.navbar {
  width: calc(100% - 40px);
  margin: 0 auto;
  border-radius: 15px;
  padding: 10px 20px;
  justify-content: space-between;
  background-color: white;
  box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
}

.container {
  display: flex;
  justify-content: space-between;
  align-items: center;
  text-align: center;
  margin: 0 auto;
  display: flex;
}

.logo {
  font-size: 24px;
  font-weight: bold;
  font-family: "Poppins", sans-serif;
  color: #333;
}

.nav-links {
  align-items: center;
  list-style: none;
  display: flex;
  margin: 0;
  padding: 0;
  gap: 20px;
}

.nav-links li {
  position: relative;
}

.nav-links li a {
  text-decoration: none;
  color: #333;
  font-size: 16px;
  font-weight: 500;
  transition: color 0.3s;
  font-family: "Poppins", sans-serif;
}

.nav-links li a:hover {
  color: #fbbf24;
}

.cart {
  position: relative;
  display: inline-block;
}

.cart-icon img {
  width: 24px;
  height: 24px;
}

.dropdown {
  cursor: pointer;
}

.dropdown-toggle img {
  width: 24px;
  height: 24px;
}

.dropdown-menu {
  position: absolute;
  top: 100%;
  right: 0;
  background: white;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
  border-radius: 8px;
  display: none;
  padding: 10px 0;
  list-style: none;
  min-width: 150px;
}

.dropdown:hover .dropdown-menu {
  display: block;
}

.dropdown-menu li {
  padding: 10px 20px;
}

.dropdown-menu li a {
  text-decoration: none;
  color: #333;
  font-size: 14px;
  font-weight: 500;
}

.dropdown-menu li a:hover {
  color: #007bff;
  background-color: rgba(0, 123, 255, 0.1);
  border-radius: 4px;
}

/* .nav-icons {
  display: flex;
  align-items: center;
  gap: 1vw;
} */

@media (max-width: 768px) {
  .header {
    padding: 0px;
  }

  .navbar {
    width: 100%;
    border-radius: 0px;
  }

  .nav-links {
    display: none;
  }

  .nav-icons {
    display: block;
  }

  .dropdown-menu {
    display: none;
  }

  .dropdown-toggle:focus+.dropdown-menu,
  .dropdown:hover .dropdown-menu {
    display: block;
  }
}
</style>
