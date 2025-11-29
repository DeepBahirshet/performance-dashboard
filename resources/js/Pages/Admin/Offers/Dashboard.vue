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
                title="Daily Redemptions"
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

            <ForecastChart
                :actualLabels="props.metrics.daily.labels"
                :actualSeries="props.metrics.daily.counts"
                :forecastLabels="props.metrics.forecast.labels"
                :forecastSeries="props.metrics.forecast.series"
                :generatedAt="props.metrics.generatedAt"
            />

            
        </div>
    </div>
</template>

<script setup>
import { ChartBarIcon } from "@heroicons/vue/24/solid";
import KpiCard from "@/Admin/Components/KpiCard.vue";
import RedemptionChart from "@/Admin/Components/RedemptionChart.vue";
import { ref, computed, onMounted } from "vue";
import BudgetUtilizationChart from '@/Admin/Components/BudgetUtilizationChart.vue';
import ForecastChart from "@/Admin/Components/ForecastChart.vue";

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

</script>

<style scoped></style>
