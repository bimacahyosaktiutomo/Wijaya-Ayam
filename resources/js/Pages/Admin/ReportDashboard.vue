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
  line_chart_data: Array,
  line_group_by: String,
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
  router.visit(route('dashboard.report'), {
    data: { group_by: groupBy.value },
    only: ['group_by', 'data', 'produkList'],
  })
}

const donutLabels = props.donut_chart_data.map(item => item.label)
const donutSeries = props.donut_chart_data.map(item => Number(item.total_terjual)) //harus pake number soalnya angkanya kebaca String

const donutOptions = ref({
  title: {
    text: 'Distribusi Penjualan Produk'
  },
  chart: {
    type: 'donut'
  },
  labels: donutLabels,
  responsive: [{
    breakpoint: 480,
    options: {
      legend: {
        position: 'bottom'
      }
    }
  }]
})

const lineGroupBy = ref(props.line_group_by || 'month')

function onLineGroupChange() {
  router.visit(route('dashboard.report'), {
    data: { line_group_by: lineGroupBy.value },
    only: ['line_chart_data', 'line_group_by'],
    preserveScroll: true,
    // preserveState: true,
  })
}

const lineLabels = props.line_chart_data.map(item => item.label);
const lineSeries = [
  {
    name: 'Total Penjualan',
    type: 'column',
    data: props.line_chart_data.map(item => Number(item.total_penjualan)),
  },
  {
    name: 'Jumlah Transaksi',
    type: 'line',
    data: props.line_chart_data.map(item => Number(item.jumlah_transaksi)),
  }
];

const lineOptions = {
  chart: {
    type: 'line',
    zoom: {
      enabled: false
    },
  },
  title: {
    text: 'Data Penjualan Bulanan',
    align: 'left'
  },
  xaxis: {
    categories: lineLabels,
  },
  yaxis: [
    {
      labels: {
        formatter: (val) => 'Rp. ' + val.toLocaleString('id-ID'),
      }
    }, 
    {
      opposite: true,
      labels: {
      formatter: val => Math.round(val)
    }
    }],
  markers: {
    size: 1
  },
  dataLabels: {
    enabled: true,
    formatter: function(val, opts) {
      if (opts.seriesIndex === 0) return 'Rp. ' + val.toLocaleString('id-ID'); // Biar Total Penjualan aja yg ada Rp. nya
      else return val;
    },
  },
  legend: {
    position: 'top',
    horizontalAlign: 'right',
    floating: true,
    offsetY: -25,
    offsetX: -5
  }
};
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
              <td colspan="4" class="bg-gray-50 px-6 py-4 space-y-4">
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

                <hr class="my-4"/>

                <div class="flex flex-col md:flex-row gap-4 items-start md:items-center">
                  <div>
                    📆 <label class="mr-2 font-medium">Rentang Tanggal:</label>
                        <input
                          type="date"
                          v-model="startDates[produk.nama_produk]"
                          @change="() => fetchProdukReportByDateRange(produk.nama_produk, startDates[produk.nama_produk], endDates[produk.nama_produk])"
                          class="border px-2 py-1 rounded mr-2"
                        />
                        s/d
                        <input
                          type="date"
                          v-model="endDates[produk.nama_produk]"
                          @change="() => fetchProdukReportByDateRange(produk.nama_produk, startDates[produk.nama_produk], endDates[produk.nama_produk])"
                          class="border px-2 py-1 rounded ml-2"
                        />
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
      <div class="bg-white p-6 rounded shadow">
        <apexchart class="flex justify-center " width="80%" type="pie" :options="donutOptions" :series="donutSeries"></apexchart>
      </div>
      <div class="bg-white p-6 rounded shadow">
        <label class="mr-2">Sortir:</label>
        <select v-model="lineGroupBy" @change="onLineGroupChange" class="border rounded px-3 py-2">
          <option value="day">Per Hari</option>
          <option value="month">Per Bulan</option>
        </select>
        <input v-if="lineGroupBy === 'day'" class="border rounded px-3 py-2" type="month" name="" id="">
        <apexchart class="flex justify-center items-center" width="100%" type="line" :options="lineOptions" :series="lineSeries"></apexchart>
      </div>
    </div>
  </DashboardLayout>
</template>