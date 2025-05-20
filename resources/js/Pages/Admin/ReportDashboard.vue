<script setup>
import DashboardLayout from '@/Layouts/DashboardLayout.vue'
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'

const props = defineProps({
  data: Array,
  group_by: String,
})

const groupBy = ref(props.group_by || 'date')

function applyGroup() {
  router.visit(route('dashboard.report'), {
    method: 'get',
    preserveState: true,
    preserveScroll: true,
    only: ['data', 'group_by'],
    data: { group_by: groupBy.value },
  })
}
</script>

<template>
  <DashboardLayout sideBarActive="report">
    <div class="bg-white p-6 rounded shadow">
      <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold">Laporan Penjualan</h1>
        <div>
          <label class="mr-2">Group By:</label>
          <select
            v-model="groupBy"
            @change="applyGroup"
            class="border rounded px-3 py-2"
          >
            <option value="date">Per Hari</option>
            <option value="month">Per Bulan</option>
            <option value="product">Per Produk</option>
          </select>
        </div>
      </div>

      <table class="min-w-full border border-gray-300">
        <thead class="bg-gray-200">
          <tr>
            <th class="px-4 py-2 border">Label</th>
            <th class="px-4 py-2 border">Total Penjualan</th>
            <th class="px-4 py-2 border" v-if="groupBy === 'product'">Total Terjual</th>
            <th class="px-4 py-2 border" v-else>Jumlah Transaksi</th>
          </tr>
        </thead>
        <tbody>
          <tr v-if="data.length === 0">
            <td colspan="3" class="px-4 py-2 text-center">Tidak ada data</td>
          </tr>
          <tr v-for="(item, idx) in data" :key="idx" class="text-center border-b hover:bg-gray-50">
            <td class="px-4 py-2 border">{{ item.label }}</td>
            <td class="px-4 py-2 border">Rp {{ item.total_penjualan.toLocaleString() }}</td>
            <td class="px-4 py-2 border">
              {{ groupBy === 'product' ? item.total_terjual : item.jumlah_transaksi }}
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </DashboardLayout>
</template>
