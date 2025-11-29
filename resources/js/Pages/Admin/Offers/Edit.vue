<template>
<div class="p-6 max-w-[80rem] mx-auto">
  <h1 class="text-2xl font-semibold">Edit Offer</h1>
  <hr class="my-4 border-gray-200 shadow-sm" />

  <OfferForm
    :initial="offer"
    :action="route('admin.offers.update', offer.id)"
    method="put"/>
</div>
</template>

<script setup>
import OfferForm from '@/Admin/Components/OfferForm.vue';
import {route} from 'ziggy-js';

const props = defineProps({
  offer: Object,
});
</script>
