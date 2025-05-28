<script setup>
import { ref, computed, watch } from 'vue'
import { router } from '@inertiajs/vue3'

const props = defineProps({
    donutData: Array,
    lineData: Array,
    line_group_by: String,
    line_selected_month: String,
    chartMode: String,
})

const donutLabels = computed(() => 
    props.chartMode === 'pie' ? props.donutData?.map(item => item.label) : []
)
const donutSeries = computed(() => 
    props.chartMode === 'pie' ? props.donutData?.map(item => Number(item.total_terjual)) : []
)

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
const lineSelectedMonth = ref(props.line_selected_month || new Date().toISOString().slice(0, 7))

watch(() => props.line_selected_month, (newValue) => {
    if (newValue) {
        lineSelectedMonth.value = newValue
    }
})

watch(() => props.line_group_by, (newValue) => {
    if (newValue) {
        lineGroupBy.value = newValue
    }
})

// function onLineGroupChange() {
//     const data = { line_group_by: lineGroupBy.value }
//     console.log();
//     if (lineGroupBy.value === 'day') {
//         data.line_selected_month = lineSelectedMonth.value
//     }

//     router.visit(route('dashboard.report'), {
//         data: data,
//         only: ['lineData', 'line_group_by', 'line_selected_month'],
//         preserveScroll: true,
//     })
// }

// function onMonthChange() {
//     if (lineGroupBy.value === 'day') {
//         onLineGroupChange()
//     }
// }

const lineLabels = computed(() => 
    props.chartMode === 'line' ? props.lineData?.map(item => item.label) : []
)

const lineSeries = computed(() => {
    if (props.chartMode !== 'line' || !props.lineData) return []
    
    return [
        {
            name: 'Total Penjualan',
            type: 'column',
            data: props.lineData.map(item => Number(item.total_penjualan)),
        },
        {
            name: 'Jumlah Transaksi',
            type: 'line',
            data: props.lineData.map(item => Number(item.jumlah_transaksi)),
        }
    ]
})

const lineOptions = computed(() => ({
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
        categories: lineLabels.value,
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
        }
    ],
    markers: {
        size: 1
    },
    dataLabels: {
        enabled: true,
        formatter: function (val, opts) {
            if (opts.seriesIndex === 0) return 'Rp. ' + val.toLocaleString('id-ID');
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
}))
</script>

<template>
    <div v-if="props.chartMode === 'pie'" >
        <apexchart class="flex justify-center" width="80%" type="pie" :options="donutOptions" :series="donutSeries">
        </apexchart>
    </div>
    <div v-else-if="props.chartMode === 'line'" >
        <!-- <div class="mb-4 flex flex-wrap items-center gap-2">
            <label class="mr-2">Sortir:</label>
            <select v-model="lineGroupBy" @change="onLineGroupChange" class="border rounded px-3 py-2">
                <option value="day">Per Hari</option>
                <option value="month">Per Bulan</option>
            </select>
            <div v-if="lineGroupBy === 'day'" class="flex items-center gap-2">
                <label class="text-sm">Bulan:</label>
                <input 
                    v-model="lineSelectedMonth" 
                    @change="onMonthChange"
                    class="border rounded px-3 py-2" 
                    type="month" 
                    name="selected_month" 
                    id="selected_month"
                >
            </div>
        </div> -->
        <apexchart class="flex justify-center items-center" width="100%" type="line" :options="lineOptions"
            :series="lineSeries"></apexchart>
    </div>
</template>