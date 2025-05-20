<script setup>
import DashboardLayout from '@/Layouts/DashboardLayout.vue'
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'
import axios from 'axios'

const props = defineProps({
  data: Array,
  group_by: String,
  produkList: Array,
  donut_chart_data: Array,
})

const groupBy = ref(props.group_by || 'date')
const openProduk = ref(null)
const produkData = ref({})

function toggleProduk(produk) {
  openProduk.value = openProduk.value === produk ? null : produk
}

function fetchProdukReport(produk, tanggal) {
  if (!tanggal) return
  axios.get(route('dashboard.report.productByDate'), {
    params: { produk, tanggal },
  }).then(res => {
    produkData.value[produk] = {
      tanggal,
      total_terjual: res.data.total_terjual ?? 0,
      total_penjualan: res.data.total_penjualan ?? 0,
    }
  })
}

function onGroupChange() {
  router.visit(route('dashboard.report'), {
    data: { group_by: groupBy.value },
    only: ['group_by', 'data', 'produkList'],
  })
}

const labels = props.donut_chart_data.map(item => item.label)
const series = props.donut_chart_data.map(item => Number(item.total_terjual)) //harus pake number soalnya angkanya kebaca String
console.log(labels)
console.log(series)

const options = ref({
  chart: {
    type: 'donut'
  },
  labels: labels,
  responsive: [{
    breakpoint: 480,
    options: {
      legend: {
        position: 'bottom'
      }
    }
  }]
})
</script>

<template>
  <DashboardLayout sideBarActive="report">
    <div class="bg-white p-6 rounded shadow">
      <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold">Laporan Penjualan</h1>
        <div>
          <label class="mr-2">Sortir:</label>
          <select
            v-model="groupBy"
            @change="onGroupChange"
            class="border rounded px-3 py-2"
          >
            <option value="date">Per Hari</option>
            <option value="month">Per Bulan</option>
            <option value="product">Per Produk per Hari</option>
          </select>
        </div>
      </div>

      <table v-if="groupBy === 'product'" class="min-w-full border border-gray-300">
        <thead class="bg-gray-200">
          <tr>
            <th class="px-4 py-2 border text-left">Nama Produk</th>
            <th class="px-4 py-2 border text-center">Total Terjual</th>
            <th class="px-4 py-2 border text-center">Total Penjualan</th>
            <th class="px-4 py-2 border text-center"></th>
          </tr>
        </thead>
        <tbody>
          <template v-for="produk in produkList" :key="produk.nama_produk">
            <tr
              class="cursor-pointer hover:bg-gray-50"
              @click="toggleProduk(produk.nama_produk)"
            >
              <td class="px-4 py-2 border">{{ produk.nama_produk }}</td>
              <td class="px-4 py-2 border text-center">{{ produk.total_terjual }}</td>
              <td class="px-4 py-2 border text-center">Rp {{ produk.total_penjualan.toLocaleString() }}</td>
              <td class="px-4 py-2 border text-center">
                {{ openProduk === produk.nama_produk ? '▲' : '▼' }}
              </td>
            </tr>

            <tr v-if="openProduk === produk.nama_produk">
              <td colspan="4" class="bg-gray-50 px-6 py-4">
                <div class="flex flex-col md:flex-row gap-4 items-start md:items-center">
                  <div>
                    📅 <label class="mr-2 font-medium">Pilih Tanggal:</label>
                    <input
                      type="date"
                      @change="e => fetchProdukReport(produk.nama_produk, e.target.value)"
                      class="border px-2 py-1 rounded"
                    />
                  </div>
                  <div>
                    🛒 <strong>Total Terjual:</strong>
                    {{ produkData[produk.nama_produk]?.total_terjual ?? '-' }}
                  </div>
                  <div>
                    💰 <strong>Total Penjualan:</strong>
                    Rp {{ (produkData[produk.nama_produk]?.total_penjualan ?? 0).toLocaleString() }}
                  </div>
                </div>
              </td>
            </tr>
          </template>
        </tbody>
      </table>

      <table v-else class="min-w-full border border-gray-300">
        <thead class="bg-gray-200">
          <tr>
            <th class="px-4 py-2 border">Tanggal</th>
            <th class="px-4 py-2 border">Total Penjualan</th>
            <th class="px-4 py-2 border">Jumlah Transaksi</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(item, idx) in data" :key="idx">
            <td class="px-4 py-2 border">{{ item.label }}</td>
            <td class="px-4 py-2 border">Rp {{ item.total_penjualan.toLocaleString() }}</td>
            <td class="px-4 py-2 border">{{ item.jumlah_transaksi }}</td>
          </tr>
        </tbody>
      </table>
    </div>

    <div class="flex flex-col items-center space-y-2 md:flex-row md:justify-between md:space-x-2 md:space-y-0 my-2">
      <div class="bg-white p-6 md:w-1/3 w-4/5 rounded shadow">
        <apexchart class="flex justify-center " width="100%" type="donut" :options="options" :series="series"></apexchart>
      </div>
      <div class="bg-white p-6 md:w-1/3 w-4/5 rounded shadow">
        <apexchart class="flex justify-center " width="100%" type="donut" :options="options" :series="series"></apexchart>
      </div>
      <div class="bg-white p-6 md:w-1/3 w-4/5 rounded shadow">
        <apexchart class="flex justify-center " width="100%" type="donut" :options="options" :series="series"></apexchart>
      </div>
    </div>
  </DashboardLayout>
</template>