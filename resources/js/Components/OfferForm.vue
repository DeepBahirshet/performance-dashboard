<template>
  <form @submit.prevent="submit">
    <div class="grid grid-cols-2 gap-4">
      <div>
        <label class="block">Name</label>
        <input v-model="form.name" type="text" />
        <div v-if="form.errors.name" class="text-red-600">{{ form.errors.name }}</div>
      </div>

      <div>
        <label>Offer Code</label>
        <input v-model="form.offer_code" type="text" />
        <div v-if="form.errors.offer_code" class="text-red-600">{{ form.errors.offer_code }}</div>
      </div>

      <div>
        <label>Start Date</label>
        <input v-model="form.start_date" type="date" />
        <div v-if="form.errors.start_date" class="text-red-600">{{ form.errors.start_date }}</div>
      </div>

      <div>
        <label>End Date</label>
        <input v-model="form.end_date" type="date" />
        <div v-if="form.errors.end_date" class="text-red-600">{{ form.errors.end_date }}</div>
      </div>

      <div>
        <label>Budget</label>
        <input v-model="form.budget" type="number" step="0.01" />
        <div v-if="form.errors.budget" class="text-red-600">{{ form.errors.budget }}</div>
      </div>

      <div>
        <label>Max Redemptions</label>
        <input v-model="form.max_redemptions" type="number" />
        <div v-if="form.errors.max_redemptions" class="text-red-600">{{ form.errors.max_redemptions }}</div>
      </div>

      <div>
        <label>Discount Type</label>
        <select v-model="form.discount_type">
          <option value="%">Percentage (%)</option>
          <option value="flat">Flat</option>
        </select>
        <div v-if="form.errors.discount_type" class="text-red-600">{{ form.errors.discount_type }}</div>
      </div>

      <div>
        <label>Discount Value</label>
        <input v-model="form.discount_value" type="number" step="0.01" />
        <div v-if="form.errors.discount_value" class="text-red-600">{{ form.errors.discount_value }}</div>
      </div>
    </div>

    <div class="mt-4">
      <button type="submit" :disabled="form.processing" class="btn-primary">
        {{ method === 'post' ? 'Create Offer' : 'Update Offer' }}
      </button>
    </div>
  </form>
</template>

<script setup>
import { ref, watch, computed } from 'vue';
import { useForm } from '@inertiajs/inertia-vue3';

const props = defineProps({
  initial: { type: Object, default: () => ({}) },
  action: { type: String, required: true },
  method: { type: String, default: 'post' },
});

const form = useForm({
  name: props.initial.name || '',
  offer_code: props.initial.offer_code || '',
  start_date: props.initial.start_date || '',
  end_date: props.initial.end_date || '',
  budget: props.initial.budget ?? 0,
  max_redemptions: props.initial.max_redemptions ?? null,
  discount_type: props.initial.discount_type || '%',
  discount_value: props.initial.discount_value ?? 0,
});

function submit() {
  if (props.method.toLowerCase() === 'post') {
    form.post(props.action);
  } else {
    form.put(props.action);
  }
}
</script>
