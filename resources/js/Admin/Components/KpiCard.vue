<template>
  <div>
    <div v-if="kpis && Object.keys(kpis).length"
         class="grid grid-cols-2 md:grid-cols-5 gap-4 mt-6">

      <div
        v-for="(value, key) in kpis"
        :key="key"
        class="bg-white p-4 rounded-lg shadow flex items-center space-x-3 border border-gray-100
               cursor-pointer transform transition-all duration-200 hover:-translate-y-1 hover:shadow-md"
      >
        <component :is="icons[key] ?? DefaultIcon" class="w-6 h-6 text-blue-600" />

        <div class="flex flex-col">
          <span class="text-xs text-gray-500">{{ labels[key] ?? key }}</span>
          <span class="text-lg font-bold">{{ formatValue(value, key) }}</span>
        </div>
      </div>

    </div>

    <div v-else class="text-sm text-gray-500 mt-6">Loading KPIs…</div>
  </div>
</template>

<script setup>

import {
  ChartBarIcon,
  CurrencyRupeeIcon,
  WalletIcon,
  ArrowTrendingUpIcon,
  ChartPieIcon
} from '@heroicons/vue/24/outline';

const DefaultIcon = ChartBarIcon;

const icons = {
  total_redemptions: ChartBarIcon,
  total_discount: CurrencyRupeeIcon,
  remaining_budget: WalletIcon,
  redemption_rate_percent: ArrowTrendingUpIcon,
  avg_daily_redemption: ChartPieIcon,
};

const labels = {
  total_redemptions: "Total Redemptions",
  total_discount: "Total Discount",
  remaining_budget: "Remaining Budget",
  redemption_rate_percent: "Redemption Rate",
  avg_daily_redemption: "Average Daily",
};

const props = defineProps({
  kpis: { type: Object, required: true }
});

function formatCurrency(val) {
  if (val === null || val === undefined) return "—";
  return Number(val).toLocaleString(undefined, {
    minimumFractionDigits: 2,
    maximumFractionDigits: 2
  });
}

function formatValue(val, key) {
  if (val === null || val === undefined) return "—";

  if (key === "total_discount" || key === "remaining_budget") {
    return "₹" + formatCurrency(val);
  }

  if (key === "redemption_rate_percent") {
    return Number(val).toFixed(1) + "%";
  }

  return val;
}
</script>
