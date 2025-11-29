<template>
    <div class="mt-6 bg-white p-4 rounded-lg shadow">
        <h3 class="font-semibold mb-3">14-Day Forecast</h3>

        <div class="h-64 relative">
            <canvas ref="forecastRef"></canvas>
        </div>
        <div v-if="props.generatedAt != null" class="text-xs text-gray-500 mt-2">
            Generated at: {{ formatLocal(props.generatedAt) }}
        </div>

    </div>
</template>

<script setup>

    import { Chart } from "chart.js/auto";
    import { ref, onMounted } from "vue";

    const props = defineProps({
        actualLabels: Array,
        actualSeries: Array,
        forecastLabels: Array,
        forecastSeries: Array,
        generatedAt: String
    });

    const forecastRef = ref(null);
    const createForecastChart = () => {
        const ctx = forecastRef.value.getContext("2d");

        const actualLabels = props.actualLabels;      
        const actualSeries = props.actualSeries;     

        const forecastLabels = props.forecastLabels;  
        const forecastSeries = props.forecastSeries;   

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

    const formatLocal = (dateStr) => {
    return new Date(dateStr).toLocaleString();
    };

    onMounted(() => {
    createForecastChart();
    });




</script>
