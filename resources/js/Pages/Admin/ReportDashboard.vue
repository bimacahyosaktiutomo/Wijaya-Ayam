<script setup>
import DashboardLayout from '@/Layouts/DashboardLayout.vue'
import Chart from '@/Components/Chart.vue'
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'
import axios from 'axios'

const props = defineProps({
  data: Array,
  group_by: String,
  produkList: Array,
  donut_chart_data: Array,
  line_chart_data: Array,
  line_group_by: String,
  line_selected_month: String,
})

const groupBy = ref(props.group_by || 'date')
const openProduk = ref(null)
const produkData = ref({})
const dateRangeData = ref({})
const startDates = ref({})
const endDates = ref({})

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

function fetchProdukReportByDateRange(produk, start, end) {
  if (!start || !end) return
  axios.get(route('dashboard.report.productByDateRange'), {
    params: { produk, start, end },
  }).then(res => {
    dateRangeData.value[produk] = {
      start,
      end,
      total_terjual: res.data.total_terjual ?? 0,
      total_penjualan: res.data.total_penjualan ?? 0,
    }
  })
}

function onGroupChange() {
  router.reload( { // Khusus ke halaman yg sama biar bisa pake preserveScroll
    data: { group_by: groupBy.value },
    only: ['group_by', 'data', 'produkList'],
    preserveScroll: true,
  })
}

const lineGroupBy = ref(props.line_group_by || 'month')
const lineSelectedMonth = ref(props.line_selected_month || new Date().toISOString().slice(0, 7))

function onLineGroupChange() {
  const data = { line_group_by: lineGroupBy.value }
  if (lineGroupBy.value === 'day') {
    data.line_selected_month = lineSelectedMonth.value
    console.log(lineSelectedMonth.value);
  }
  
  router.reload({ // Khusus ke halaman yg sama biar bisa pake preserveScroll
    data: data,
    only: ['line_chart_data', 'line_group_by', 'line_selected_month'],
    preserveScroll: true,
  })
}

function onMonthChange() {
  if (lineGroupBy.value === 'day') {
    onLineGroupChange()
  }
}
</script>

<template>
  <DashboardLayout sideBarActive="report">
    <div class="bg-white p-6 rounded shadow">
      <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold">Laporan Penjualan</h1>
        <div>
          <label class="mr-2">Sortir:</label>
          <select v-model="groupBy" @change="onGroupChange" class="border rounded px-3 py-2">
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
            <tr class="cursor-pointer hover:bg-gray-50" @click="toggleProduk(produk.nama_produk)">
              <td class="px-4 py-2 border">{{ produk.nama_produk }}</td>
              <td class="px-4 py-2 border text-center">{{ produk.total_terjual }}</td>
              <td class="px-4 py-2 border text-center">Rp {{ produk.total_penjualan.toLocaleString() }}</td>
              <td class="px-4 py-2 border text-center">
                {{ openProduk === produk.nama_produk ? '▲' : '▼' }}
              </td>
            </tr>

            <tr v-if="openProduk === produk.nama_produk">
              <td colspan="4" class="bg-gray-50 px-6 py-4 space-y-4">
                <div class="flex flex-col md:flex-row gap-4 items-start md:items-center">
                  <div>
                    📅 <label class="mr-2 font-medium">Pilih Tanggal:</label>
                    <input type="date" @change="e => fetchProdukReport(produk.nama_produk, e.target.value)"
                      class="border px-2 py-1 rounded" />
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

                <hr class="my-4" />

                <div class="flex flex-col md:flex-row gap-4 items-start md:items-center">
                  <div>
                    📆 <label class="mr-2 font-medium">Rentang Tanggal:</label>
                    <input type="date" v-model="startDates[produk.nama_produk]"
                      @change="() => fetchProdukReportByDateRange(produk.nama_produk, startDates[produk.nama_produk], endDates[produk.nama_produk])"
                      class="border px-2 py-1 rounded mr-2" />
                    s/d
                    <input type="date" v-model="endDates[produk.nama_produk]"
                      @change="() => fetchProdukReportByDateRange(produk.nama_produk, startDates[produk.nama_produk], endDates[produk.nama_produk])"
                      class="border px-2 py-1 rounded ml-2" />
                  </div>
                  <div>
                    🛒 <strong>Total Terjual:</strong>
                    {{ dateRangeData[produk.nama_produk]?.total_terjual ?? '-' }}
                  </div>
                  <div>
                    💰 <strong>Total Penjualan:</strong>
                    Rp {{ (dateRangeData[produk.nama_produk]?.total_penjualan ?? 0).toLocaleString() }}
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

    <!-- <div class="flex flex-col items-center space-y-2 md:flex-row md:justify-between md:space-x-2 md:space-y-0 my-2 "> -->
    <div class="grid md:grid-cols-2 md:space-x-5 space-y-2 md:space-y-0 my-2">
      <Chart class="bg-white p-6 rounded shadow" chartMode="pie" :donutData="props.donut_chart_data"></Chart>
      <div class="bg-white p-6 rounded shadow">
        <div class="mb-4 flex flex-wrap items-center gap-2">
          <label class="mr-2">Sortir:</label>
          <select v-model="lineGroupBy" @change="onLineGroupChange" class="border rounded px-3 py-2">
            <option value="day">Per Hari</option>
            <option value="month">Per Bulan</option>
          </select>
          <div v-if="lineGroupBy === 'day'" class="flex items-center gap-2">
            <label class="">Bulan:</label>
            <input v-model="lineSelectedMonth" @change="onMonthChange" class="border rounded px-3 py-2" type="month"
              name="selected_month" id="selected_month">
          </div>
        </div>
        <Chart chartMode="line" :line-data="line_chart_data" :line_group_by="line_group_by" :line_selected_month="line_selected_month"></Chart>
      </div>
    </div>
  </DashboardLayout>
</template>