<template>
  <div class="p-6">
    <h1 class="text-xl mb-4">Edit Offer</h1>
    <OfferForm :initial="offer" :action="route('admin.offers.update', offer.id)" method="put" />
  </div>
</template>

<script setup>
import OfferForm from './Components/OfferForm.vue';

const props = defineProps({
  offer: Object,
});
</script>
