<template>

    <div class="mt-6">
                <div
                    class="bg-white rounded-lg shadow p-4 border border-gray-100"
                >
                    <h3 class="text-lg font-semibold mb-3">
                        Budget Utilization
                    </h3>
                    <div class="pie-wrapper">
                        <canvas ref="canvasRef"></canvas>
                    </div>
                </div>
            </div>
            


</template>   


<script setup>

    import {ref, onMounted, onBeforeUnmount } from 'vue';
    import { Chart } from "chart.js/auto";

    const props = defineProps({
        labels: {type: Array, default: () => []},
        data: {type: Array, default: () => []},
        percent: {type: Number, default: 0},
    });

    const canvasRef = ref(null);
    let chartInstance = null;

    const createChart = () => {
    // destroy previous instance if any
    if (chartInstance) {
        chartInstance.destroy();
        chartInstance = null;
    }

    const ctx = canvasRef.value.getContext("2d");

    const spent = props.data[0] || 0;
    const remaining = props.data[1] || 0;
    const total = spent + remaining;

    const percent = total === 0 ? 0 : Math.round((spent / total) * 100);

    // human readable center text
    const centerSubText = `${spent.toLocaleString()} of ${total.toLocaleString()}`;

    // color logic
    const backgroundColor =
        percent >= 80
            ? ["#FF6384", "#E8E8EA"] // red
            : percent >= 50
            ? ["#FFCE56", "#E8E8EA"] // yellow
            : ["#4BC0C0", "#E8E8EA"]; // green

    // chart init
    chartInstance = new Chart(ctx, {
        type: "doughnut",
        data: {
            labels: props.labels,
            datasets: [
                {
                    data: [spent, remaining],
                    backgroundColor,
                    borderWidth: 0,
                },
            ],
        },
        options: {
        responsive: true,
        maintainAspectRatio: false,
        cutout: "70%", // hollow size
        animation: {
            animateRotate: true,
            duration: 800,
        },
        plugins: {
            tooltip: {
            callbacks: {
                label(context) {
                const value = Number(context.raw) || 0;
                const pct = sum === 0 ? 0 : ((value / sum) * 100).toFixed(1);
                return `${context.label}: ${value.toLocaleString()} (${pct}%)`;
                }
            }
            },
            legend: {
            display: true,
            position: "bottom"
            }
        },
        },
        plugins: [
        {
            id: "centerText",
            afterDraw(chart) {
            const { ctx, chartArea: { width, height } } = chart;
            ctx.save();

            // main percent
            ctx.font = 'bold 28px system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial';
            ctx.fillStyle = "#111827";
            ctx.textAlign = "center";
            ctx.textBaseline = "middle";
            ctx.fillText(`${percent}%`, width / 2, height / 2 - 6);

            // subtext
            ctx.font = '12px system-ui, -apple-system, "Segoe UI", Roboto';
            ctx.fillStyle = "#6b7280";
            ctx.fillText(centerSubText, width / 2, height / 2 + 18);

            ctx.restore();
            }
        }
        ],
    });
    };


    onMounted(() => createChart());

    onBeforeUnmount(() => chartInstance?.destroy());

</script>