<template>
  <div class="bg-white rounded-xl shadow-lg p-4 w-[80rem]">
  <form @submit.prevent="submit" class="space-y-6">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
      <!-- Name -->
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
        <input
          v-model="form.name"
          type="text"
          class="mt-1 block w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm shadow-sm
                 focus:border-sky-500 focus:ring-1 focus:ring-sky-500 outline-none"
        />
        <p v-if="form.errors.name" class="mt-1 text-xs text-red-600">
          {{ form.errors.name }}
        </p>
      </div>

      <!-- Offer Code -->
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Offer Code</label>
        <input
          v-model="form.offer_code"
          type="text"
          class="mt-1 block w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm shadow-sm
                 focus:border-sky-500 focus:ring-1 focus:ring-sky-500 outline-none"
        />
        <p v-if="form.errors.offer_code" class="mt-1 text-xs text-red-600">
          {{ form.errors.offer_code }}
        </p>
      </div>

      <!-- Start Date -->
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Start Date</label>
        <input
          v-model="form.start_date"
          type="date"
          class="mt-1 block w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm shadow-sm
                 focus:border-sky-500 focus:ring-1 focus:ring-sky-500 outline-none"
        />
        <p v-if="form.errors.start_date" class="mt-1 text-xs text-red-600">
          {{ form.errors.start_date }}
        </p>
      </div>

      <!-- End Date -->
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">End Date</label>
        <input
          v-model="form.end_date"
          type="date" :min="form.start_date"
          class="mt-1 block w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm shadow-sm
                 focus:border-sky-500 focus:ring-1 focus:ring-sky-500 outline-none"
        />
        <p v-if="form.errors.end_date" class="mt-1 text-xs text-red-600">
          {{ form.errors.end_date }}
        </p>
      </div>

      <!-- Budget -->
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Budget</label>
        <input
          v-model="form.budget"
          type="number"
          step="0.01"
          class="mt-1 block w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm shadow-sm
                 focus:border-sky-500 focus:ring-1 focus:ring-sky-500 outline-none"
        />
        <p v-if="form.errors.budget" class="mt-1 text-xs text-red-600">
          {{ form.errors.budget }}
        </p>
      </div>

      <!-- Max Redemptions -->
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Max Redemptions</label>
        <input
          v-model="form.max_redemptions"
          type="number"
          class="mt-1 block w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm shadow-sm
                 focus:border-sky-500 focus:ring-1 focus:ring-sky-500 outline-none"
        />
        <p v-if="form.errors.max_redemptions" class="mt-1 text-xs text-red-600">
          {{ form.errors.max_redemptions }}
        </p>
      </div>

      <!-- Discount Type -->
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Discount Type</label>
        <select
          v-model="form.discount_type"
          class="mt-1 block w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm shadow-sm
                 focus:border-sky-500 focus:ring-1 focus:ring-sky-500 outline-none"
        >
          <option value="%">Percentage (%)</option>
          <option value="flat">Flat</option>
        </select>
        <p v-if="form.errors.discount_type" class="mt-1 text-xs text-red-600">
          {{ form.errors.discount_type }}
        </p>
      </div>

      <!-- Discount Value -->
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Discount Value</label>
        <input
          v-model="form.discount_value"
          type="number"
          step="0.01"
          class="mt-1 block w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm shadow-sm
                 focus:border-sky-500 focus:ring-1 focus:ring-sky-500 outline-none"
        />
        <p v-if="form.errors.discount_value" class="mt-1 text-xs text-red-600">
          {{ form.errors.discount_value }}
        </p>
      </div>
    </div>

    <div class="pt-4 flex justify-end">
      <button
        type="submit"
        :disabled="form.processing"
        class="inline-flex items-center px-4 py-2 rounded-lg bg-sky-600 text-white text-sm font-medium
               hover:bg-sky-700 disabled:opacity-60 disabled:cursor-not-allowed transition"
      >
        {{ method === 'post' ? 'Create Offer' : 'Update Offer' }}
      </button>
    </div>
  </form>
  </div>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3';
import { toast } from "vue3-toastify";

const props = defineProps({
  initial: { type: Object, default: () => ({}) },
  action: { type: String, required: true },
  method: { type: String, default: 'post' },
})

const normalizeDate = (value) => {
  if (!value) return ''
  // value = '2025-11-14T00:00:00.000000Z'
  return value.substring(0, 10) // '2025-11-14'
}

const form = useForm({
  name: props.initial.name || '',
  offer_code: props.initial.offer_code || '',
  start_date: normalizeDate(props.initial.start_date),
  end_date: normalizeDate(props.initial.end_date),
  budget: props.initial.budget ?? 0,
  max_redemptions: props.initial.max_redemptions ?? null,
  discount_type: props.initial.discount_type || '%',
  discount_value: props.initial.discount_value ?? 0,
})

function submit() {
  if (props.method.toLowerCase() === 'post') {
    form.post(props.action);
  } else {
    form.put(props.action);
  }
}

</script>
