<script setup>
import { Link, usePage } from '@inertiajs/vue3';
import { defineProps } from 'vue';

defineProps({
    sideBarActive: String,
});
</script>

<template>
    <div @click="toggleSideBar()" id="overlay" class="hidden fixed inset-0 bg-black bg-opacity-50 z-20"></div>
    <section class="flex bg-slate-100">
        <!-- Sidebar -->
        <div id="sidebar"
            class="flex flex-col fixed z-50 bg-gray-800 -translate-x-full h-screen w-52 md:z-0 md:flex-shrink-0 md:flex-none md:top-0 md:sticky md:translate-x-0 md:w-64 px-6 border-r-2 border-r-gray-400 shadow-sm overflow-auto text-indigo-50 text-md hover-anim">
            <Link :href="route('home')" class="max-w-full self-center h-20 flex items-center">
                <!-- LOGO UDAH DI CUSTOM -->
                <img src="../assets/LogoWijayaAyam2.png" alt="" class="invert md:w-36 w-20 object-contain"> 
            </Link>
            <ul class="font-semibold md:text-lg text-white space-y-6 border-y-2 border-y-gray-400 py-8">
                <li :class="{ 'text-amber-400': sideBarActive === 'product', }"
                    class="cursor-pointer hover-anim hover:text-amber-400">
                    <Link :href="route('dashboard.product')" class="sidebar-item flex justify-between items-center">
                    <i class="sidebar-icon w-1/3 fa-solid fa-boxes-stacked"></i>
                    <span class="w-full ">Produk</span>
                    </Link>
                </li>
                <li :class="{ 'text-amber-400': sideBarActive === 'user', }"
                    class="cursor-pointer hover-anim hover:text-amber-400">
                    <Link :href="route('dashboard.user')" class="sidebar-item flex justify-between items-center">
                    <i class="sidebar-icon w-1/3 fa-solid fa-user"></i>
                    <span class="w-full">User</span>
                    </Link>
                </li>
                <li :class="{ 'text-amber-400': sideBarActive === 'order', }"
                    class="cursor-pointer hover-anim hover:text-amber-400">
                    <Link :href="route('dashboard.order')" class="sidebar-item flex justify-between items-center">
                    <i class="sidebar-icon w-1/3 fa-solid fa-cart-flatbed"></i>
                    <span class="w-full">Order</span>
                    </Link>
                </li>
                <!-- ✅ Tambahkan Report di sini -->
                <li :class="{ 'text-amber-400': sideBarActive === 'report' }"
                    class="cursor-pointer hover-anim hover:text-amber-400">
                    <Link :href="route('dashboard.report')" class="sidebar-item flex justify-between items-center">
                        <i class="sidebar-icon w-1/3 fa-solid fa-chart-column"></i>
                        <span class="w-full">Report</span>
                    </Link>
                </li>
            </ul>
            <Link :href="route('home')"
                class="mt-auto mb-6 font-semibold text-white hover:text-amber-400 transition flex justify-between items-center">
            <i class="sidebar-icon w-1/3 fa-solid fa-house"></i>
            <span class="w-full">Back</span>
            </Link>
        </div>

        <div class="flex-grow">
            <!-- Header -->
            <header
                class="flex flex-row justify-between w-full sticky md:h-20 top-0 z-10 shadow-sm bg-white py-4 px-2 space-x-2 border-b-2">
                <div id="hamburger-button-container"
                    class="flex justify-evenly items-center w-2/12 hover-anim md:hidden">
                    <button id="hamburger-button" class="" @click="toggleSideBar()">
                        <i class="xs:block hidden fa-solid fa-bars"></i>
                        <i id="hidden-hamburger-button" class="xs:hidden fa-solid fa-bars hover-anim"></i>
                    </button>
                </div>
                <div class="items-center space-x-7 font-bold md:text-xl text-gray-800 pt-7 pb-6 md:flex hidden">
                    <span><i class="fa-solid fa-chart-pie"></i></span>
                    <h1>DASHBOARD</h1>
                </div>
                <div class="flex flex-col px-2 self-center">
                    <nav class="flex md:justify-between justify-end w-full md:space-x-10">
                        <div class="flex flex-row">
                            <div id="UserDropdown"
                                class="dropdown dropdown-hover dropdown-bottom dropdown-end flex cursor-pointer p-1 rounded-md md:hover:bg-amber-200 hover-anim">
                                <!-- <Link :href="route('logout')" method="post" as="button" class="flex p-2"> -->
                                <button
                                    class="flex text-gray-800 md:text-2xl md:mr-2 mr-0 md:p-0 px-1 hover:bg-amber-200 rounded-lg hover-anim">
                                    <i class="self-center fa-regular fa-user xs:align-top hover:text-indigo-800"></i>
                                </button>
                                <div class="object-contain md:flex md:flex-col md:justify-center hidden">
                                    <p class="text-xs">Welcome back,</p>
                                    <p class="font-semibold"> {{ $page.props.auth.user.name }}</p>
                                </div>
                                <!-- </Link> -->

                                <!-- Dropdown Item -->
                                <ul tabindex="0"
                                    class="dropdown-content menu bg-base-100 rounded-box z-[1] w-44 p-2 shadow">
                                    <li>
                                        <Link :href="route('home')">Home</Link>
                                    </li>
                                    <li>
                                        <Link :href="route('product')">Product</Link>
                                    </li>
                                    <li>
                                        <Link :href="route('about')">About</Link>
                                    </li>
                                    <li>
                                        <Link :href="route('logout')" method="post" as="button" class="text-red-500">
                                        Logout</Link>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </nav>
                </div>
            </header>

            <!-- Content -->
            <main class="flex-grow p-3">
                <slot />
            </main>
        </div>
    </section>
</template>

<script>
let isMinimized = true;
function toggleSideBar() {
    const sidebar = document.getElementById("sidebar");
    const overlay = document.getElementById('overlay');
    const hamburger_button_container = document.getElementById("hamburger-button-container");
    const hidden_hamburger_button = document.getElementById("hidden-hamburger-button");
    const descendant = sidebar.querySelectorAll("i, span, #sidebar-name");
    const sidebar_item = sidebar.querySelectorAll(".sidebar-item");
    if (isMinimized) {
        sidebar.classList.remove("-translate-x-full");
        sidebar.classList.add("translate-x-0");
        hidden_hamburger_button.classList.add("rotate-90");
        hidden_hamburger_button.classList.remove("fa-bars");
        hidden_hamburger_button.classList.add("fa-x");
        overlay.classList.remove("hidden");
    }
    else {
        sidebar.classList.add("-translate-x-full");
        sidebar.classList.remove("translate-x-0");
        hidden_hamburger_button.classList.remove("rotate-90");
        hidden_hamburger_button.classList.remove("fa-x");
        hidden_hamburger_button.classList.add("fa-bars");
        overlay.classList.add("hidden");
    }
    // }
    isMinimized = !isMinimized;
}

window.addEventListener('resize', () => {
    if (window.innerWidth >= 760) { // Check if the width has reached 768
        if (isMinimized === false) {
            toggleSideBar();
            // console.log('click');
        }
    }
});


</script>