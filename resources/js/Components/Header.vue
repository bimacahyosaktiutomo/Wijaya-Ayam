<script setup>
import { Link } from '@inertiajs/vue3';

const props = defineProps({
  canLogin: Boolean,
  canRegister: Boolean,
});
</script>

<!-- Alternativ, bikin propsnya di script laen soalnya gak bisa di setup -->
<script>
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
      <div class="logo">Logo</div>
      <ul class="nav-links">
        <li>
          <RouterLink to="/">
            <a>Home</a>
          </RouterLink>
        </li>
        <li>
          <RouterLink to="/product">
            <a>Product</a>
          </RouterLink>
        </li>
        <li>
          <RouterLink to="/about">
            <a>About</a>
          </RouterLink>
        </li>
      </ul>
      <div class="flex items-center space-x-4">
        <li class="cart">
          <RouterLink to="/cart">
            <a class="cart-icon">
              <img src="../assets/shopping-cart.png" alt="Cart" />
            </a>
          </RouterLink>
        </li>
        <li class="dropdown dropdown-hover dropdown-end">
          <button class="text-xl"><i class="fa-solid fa-user"></i></button>
          <ul class="dropdown-content menu bg-base-100 rounded-box z-[1] w-40 p-2 shadow">
            <li>
              <Link 
              v-if="canLogin"
              :href="route('login')">
              Login
            </Link>
          </li>
          <li>
            <Link 
                v-if="canRegister"
                :href="route('register')">
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
  color: #007bff;
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
