<template>
    <div class="flex justify-center mt-6">
  <div class="p-6">
    <div class="flex items-center justify-between mb-4">
      <h1 class="text-2xl font-semibold">Offers</h1>
        <Link
            :href="route('admin.offers.create')"
            class="ml-2 bg-emerald-600 px-4 py-2 text-white rounded-lg hover:bg-emerald-700 transition"
          >
            New Offer
          </Link>
    </div>

    <div class="mb-4">
      <input v-model="filters.q" @keyup.enter="search" placeholder="Search offers..." class="input" />
      <button @click="search" class="btn ml-2 bg-emerald-600 px-4 py-2 text-white rounded-lg hover:bg-emerald-700 transition">Search</button>
    </div>

  
    <table class="bg-white rounded-xl shadow-lg p-4 w-[80rem]">
  <thead>
    <tr class="bg-gray-100">
      <th class="py-3 px-4 font-semibold ">Name</th>
      <th class="py-3 px-4 font-semibold ">Code</th>
      <th class="py-3 px-4 font-semibold ">Validity</th>
      <th class="py-3 px-4 font-semibold ">Budget</th>
      <th class="py-3 px-4 font-semibold ">Redemptions</th>
      <th class="py-3 px-4 font-semibold ">Actions</th>
    </tr>
  </thead>

  <tbody>
    <tr
      v-for="offer in offers.data"
      :key="offer.id"
      class=" hover:bg-gray-50"
    >
      <td class="py-3 px-4">{{ offer.name }}</td>
      <td class="py-3 px-4">{{ offer.offer_code }}</td>
      <td class="py-3 px-4">{{ formatDate(offer.start_date) }} → {{ formatDate(offer.end_date) }}</td>
      <td class="py-3 px-4">{{ formatCurrency(offer.budget) }}</td>
      <td class="py-3 px-4">{{ offer.redemptions_count ?? 0 }}</td>
      <td class="py-3 px-4 text-right">
        <Link
          :href="route('admin.offers.edit', offer.id)"
          class="px-4 py-2 bg-sky-600 text-white rounded-lg hover:bg-sky-700 transition mr-3">
            <PencilSquareIcon class="w-5 h-5" />
        </Link>

        <form
          :action="route('admin.offers.destroy', offer.id)"
          method="post"
          @submit.prevent="destroy(offer.id)"
          class="inline">
          <button
            type="submit"
            class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-all duration-200">
            <TrashIcon class="w-5 h-5" />
          </button>
        </form>
      </td>
    </tr>
  </tbody>
</table>



    <div class="mt-4">
      <Pagination :links="offers.links" />
    </div>
  </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { Link } from '@inertiajs/vue3';
import {route} from 'ziggy-js'
import { Inertia } from '@inertiajs/inertia';
import { usePage } from '@inertiajs/inertia-vue3';
import Pagination from '../../Shared/Pagination.vue';
import { PencilSquareIcon, TrashIcon } from '@heroicons/vue/24/solid';


const props = defineProps({
  offers: Object,
  filters: Object,
});

const page = usePage();
const filters = ref(props.filters ?? { q: '' });

function search() {
  Inertia.get(route('admin.offers.index'), { q: filters.value.q }, { preserveState: true, replace: true });
}

function destroy(id) {
  if (!confirm('Delete this offer?')) return;
  Inertia.delete(route('admin.offers.destroy', id));
}

function formatDate(date) {
  if (!date) return '-';
  return new Date(date).toLocaleDateString();
}
function formatCurrency(val) {
  return Number(val).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
}
</script>

<style scoped>

</style>
