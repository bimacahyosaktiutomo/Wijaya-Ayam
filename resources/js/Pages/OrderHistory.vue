<script setup>
import { usePage, Link, router} from '@inertiajs/vue3';
import Header from '@/Components/Header.vue';
import moment from "moment";
import 'moment/locale/id'
const { orders } = usePage().props;

moment.locale('id');

function getDetails(orderId) {
    router.get(`/order/${orderId}/details`);
}
</script>

<template>
    <Header/>
    <section class="sm:px-16 pt-24 p-2 bg-slate-100 min-h-[100vh]">
        <div class="space-y-4">
            <div class="flex justify-between">
                <h1 class="text-xl font-semibold self-center">
                    Order History
                </h1>
                <button id="orderHistorySortButton" @click="sortOrderHistory()" class="flex border border-amber-500 bg-white rounded-xl px-4 py-2 space-x-4 hover:bg-amber-500 hover:text-white hover-anim">
                    <p class="font-semibold">
                        Terbaru
                    </p>
                    <span class=""><i class="flex-none text-xs md:text-base fa-solid fa-chevron-down transition-transform"></i></span>
                </button>
            </div>
            <div class="flex flex-col justify-center space-y-1 text-xs sm:text-base">
                <div class="flex p-3 border font-bold bg-white">
                    <div class="basis-1/6 text-center self-center">
                        <h1>
                            Id Pemesanan
                        </h1>
                    </div>
                    <div class="sm:basis-2/6 basis-3/6 text-center self-center">
                        <h1>
                            Nama
                        </h1>
                    </div>
                    <div class="sm:basis-1/6 basis-3/6 text-center self-center hidden sm:block">
                        <h1>
                            No Telepon
                        </h1>
                    </div>
                    <div class="sm:basis-1/6 basis-3/6 text-center self-center ">
                        <h1>
                            Status
                        </h1>
                    </div>
                    <div class="basis-1/6 text-center self-center ">
                        <h1>
                            Tanggal
                        </h1>
                    </div>
                </div>
                <div id="orderHistoryContainer" class="flex flex-col shadow-sm">
                    <div v-if="orders.length === 0" class="w-full p-2 bg-white text-center self-center">
                        <h1>
                            Belum ada melakukan pemesanan
                        </h1>
                    </div>
                    <div v-for="order in orders" @click="getDetails(order.id_pemesanan)" class="flex p-3 border my-1  bg-white hover:bg-slate-50 transition cursor-pointer">
                        <div class="basis-1/6 text-center self-center">
                            <h1>
                                {{ order.id_pemesanan }}
                            </h1>
                        </div>
                        <div class="sm:basis-2/6 basis-3/6 text-center self-center">
                            <h1>
                                {{ order.nama_pelanggan }}
                            </h1>
                        </div>
                        <div class="sm:basis-1/6 basis-3/6 text-center self-center hidden sm:block">
                            <h1>
                                {{ order.no_telepon }}
                            </h1>
                        </div>
                        <div class="sm:basis-1/6 basis-3/6 text-center self-center">
                            <h1 v-if="order.status_pemesanan == 'Diantar'" class="text-amber-500">
                                {{ order.status_pemesanan }}
                            </h1>
                            <h1 v-else class="text-green-400">
                                {{ order.status_pemesanan }}
                            </h1>
                        </div>
                        <div class="basis-1/6 text-center self-center">
                            <h1>
                                {{ moment(order.created_at).format("LLLL") }}
                            </h1>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<script>
function sortOrderHistory() {
    var _a, _b;
    const historyContainer = document.getElementById('orderHistoryContainer');
    const orderHistorySortButton = document.getElementById('orderHistorySortButton');
    const sortTextElement = orderHistorySortButton.querySelector('p');
    if (historyContainer && orderHistorySortButton) {
        historyContainer.classList.toggle('flex-col');
        historyContainer.classList.toggle('flex-col-reverse');
        (_a = orderHistorySortButton.querySelector('i')) === null || _a === void 0 ? void 0 : _a.classList.toggle('rotate-180');
        if (sortTextElement) {
            const currentText = (_b = sortTextElement.textContent) === null || _b === void 0 ? void 0 : _b.trim();
            sortTextElement.textContent = currentText === "Terbaru" ? "Terlama" : "Terbaru";
        }
    }
}
</script>