<template>
    <div class="flex justify-center mt-6 px-4">
        <div class="bg-white p-6 w-full max-w-5xl">
            <!-- Header card -->
            <div
                class="bg-gray-50 rounded-lg shadow p-4 mt-4 border border-gray-200"
            >
                <div class="flex items-center mb-2">
                    <ChartBarIcon class="w-6 h-6 text-blue-600" />
                    <span class="text-2xl font-semibold ml-2"
                        >Dashboard Statistics</span
                    >
                </div>
                <div class="text-gray-600 text-base ml-8">
                    Offer – {{ offer?.name ?? "N/A" }}
                </div>
            </div>

            <KpiCard :kpis="metrics.kpis" />

            <RedemptionChart
                title="Daily Redemptions (Last 30 days)"
                :type="'bar'"
                :labels="metrics.daily.labels"
                :data="metrics.daily.counts"
                datasetLabel="Daily Redemptions"
            />

            <RedemptionChart
                title="Cumulative Redemption Trend"
                type="line"
                :labels="cumulativeLabels"
                :data="cumulativeData"
                datasetLabel="Cumulative Redemptions"
            />

            <BudgetUtilizationChart
              :labels="['Spent','Remaining']"
              :data="[metrics.budget_utilization.spent, metrics.budget_utilization.budgetRemaining]"
              :percent="metrics.budget_utilization.percentage"
            />


            <div class="mt-6 bg-white p-4 rounded-lg shadow">
    <h3 class="font-semibold mb-3">14-Day Forecast</h3>

    <div class="h-64 relative">
        <canvas ref="forecastRef"></canvas>
    </div>
</div>


            
        </div>
    </div>
</template>

<script setup>
import { ChartBarIcon } from "@heroicons/vue/24/solid";
import KpiCard from "@/Admin/Components/KpiCard.vue";
    import { Chart } from "chart.js/auto";
import RedemptionChart from "@/Admin/Components/RedemptionChart.vue";
import { ref, computed, onMounted } from "vue";
import BudgetUtilizationChart from '@/Admin/Components/BudgetUtilizationChart.vue';

const props = defineProps({
  offer: Object,
  metrics: Object,
});

const cumulativeLabels = computed(() =>
    props.metrics.cumulative.map((item) => item.date)
);

const cumulativeData = computed(() =>
    props.metrics.cumulative.map((item) => item.cumulative)
);
const forecastRef = ref(null);
const createForecastChart = () => {
    const ctx = forecastRef.value.getContext("2d");

    const actualLabels = props.metrics.daily.labels;      
    const actualSeries = props.metrics.daily.counts;     

    const forecastLabels = props.metrics.forecast.labels;  
    const forecastSeries = props.metrics.forecast.series;   

    const totalActual = actualLabels.length;
    let chartInstance = null;
    chartInstance = new Chart(ctx, {
        type: "line",
        data: {
            labels: [...actualLabels, ...forecastLabels],
            datasets: [
                {
                    label: "Actual",
                    data: [...actualSeries, ...Array(forecastLabels.length).fill(null)],
                    borderColor: "#4BC0C0",
                    borderWidth: 2,
                    tension: 0.3,
                },
                {
                    label: "Forecast",
                    data: [...Array(actualSeries.length).fill(null), ...forecastSeries],
                    borderColor: "#FF6384",
                    borderWidth: 2,
                    borderDash: [6, 6],
                    tension: 0.3,
                },
            ],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
           scales: {
    x: {
        grid: {
            display: true,
            drawOnChartArea: true,
        },
        ticks: {
            autoSkip: true,
            maxTicksLimit: 10,
            maxRotation: 0,
        },
    },
    y: {
        beginAtZero: true,
    },
},
            plugins: {
                legend: {
                    position: "bottom",
                },
            },
        },
    });
};


onMounted(() => {
  createForecastChart();
})

</script>

<style scoped></style>
