<template>
  <div class="p-6">
    <h1 class="text-xl mb-4">Create Offer</h1>
    <OfferForm :initial="{}" :action="route('admin.offers.store')" method="post" />
  </div>
</template>

<script setup>
import OfferForm from './Components/OfferForm.vue';
</script>
