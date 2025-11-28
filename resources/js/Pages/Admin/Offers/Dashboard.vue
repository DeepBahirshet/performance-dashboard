<template>
  <div class="flex justify-center mt-6 px-4">
    <div class="bg-white p-6 w-full max-w-5xl">

      <!-- Header card -->
      <div class="bg-gray-50 rounded-lg shadow p-4 mt-4 border border-gray-200">
        <div class="flex items-center mb-2">
          <ChartBarIcon class="w-6 h-6 text-blue-600" />
          <span class="text-2xl font-semibold ml-2">Dashboard Statistics</span>
        </div>
        <div class="text-gray-600 text-base ml-8">
          Offer – {{ offer?.name ?? 'N/A' }}
        </div>
      </div>

      <!-- KPI grid -->
      <div v-if="kpis" class="grid grid-cols-2 md:grid-cols-5 gap-4 mt-6">
        <div
          v-for="(value, key) in kpis"
          :key="key"
          class="bg-white p-4 rounded-lg shadow flex items-center space-x-3 border border-gray-100"
        >
          <component
            :is="kpiIcons[key] ?? DefaultIcon"
            class="w-6 h-6 text-blue-600"
          />
          <div class="flex flex-col">
            <span class="text-xs text-gray-500">{{ kpiLabels[key] ?? key }}</span>
            <span class="text-lg font-bold">{{ formatValue(value, key) }}</span>
          </div>
        </div>
      </div>

      <!-- placeholder while loading -->
      <div v-else class="text-sm text-gray-500 mt-6">Loading KPIs…</div>
    </div>
  </div>
</template>

<script setup>

import { 
  ChartBarIcon,
  CurrencyRupeeIcon,
  WalletIcon,
  ArrowTrendingUpIcon,
  ChartPieIcon
} from "@heroicons/vue/24/solid";

const props = defineProps({
    offer: Object,
    labels: Array,
    dailyCounts: Array,
    cumulative: Array,
    kpis: Object,
    forecast: Array,
    forecastLabels: Array,
    budget: Number,
});


// simple icon mapping
const kpiIcons = {
  total_redemptions: ChartBarIcon,
  total_discount: CurrencyRupeeIcon,
  remaining_budget: WalletIcon,
  redemption_rate_percent: ArrowTrendingUpIcon,
  avg_daily_redemption: ChartPieIcon,
};

// label mapping
const kpiLabels = {
  total_redemptions: 'Total Redemptions',
  total_discount: 'Total Discount',
  remaining_budget: 'Remaining Budget',
  redemption_rate_percent: 'Redemption Rate',
  avg_daily_redemption: 'Avg Daily',
};

// fallback icon (use ChartBarIcon as default)
const DefaultIcon = ChartBarIcon;

function formatCurrency(val) {
  if (val === null || val === undefined) return '—';
  return Number(val).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
}

function formatValue(val, key) {
  if (val === null || val === undefined) return '—';
  if (key === 'total_discount' || key === 'remaining_budget') return '₹' + formatCurrency(val);
  if (key === 'redemption_rate_percent') return Number(val).toFixed(1) + '%';
  return val;
}

</script>
