<template>
  <div class="mt-6">
    <div class="bg-white rounded-lg shadow p-4 border border-gray-100">
      <h3 class="text-lg font-semibold mb-3">{{ title }}</h3>
      <canvas ref="chartCanvas"></canvas>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, watch } from "vue";
import { Chart } from "chart.js/auto";

const props = defineProps({
  title: { type: String, required: true },
  type: { type: String, default: "bar" },
  labels: { type: Array, default: () => [] },
  data: { type: Array, default: () => [] },
  datasetLabel: { type: String, default: "Dataset" },
});

const chartCanvas = ref(null);
let chartInstance = null;

const renderChart = () => {
  if (chartInstance) chartInstance.destroy();

  chartInstance = new Chart(chartCanvas.value, {
    type: props.type,
    data: {
      labels: props.labels,
      datasets: [
        {
          label: props.datasetLabel,
          data: props.data,
          borderWidth: 2,
          tension: 0.3,
        },
      ],
    },
    options: {
      responsive: true,
      plugins: {
        legend: { display: false },
      },
      scales: {
        x: { title: { display: true, text: "Date" } },
        y: { title: { display: true, text: "Value" } },
      },
    },
  });
};

onMounted(renderChart);

</script>
