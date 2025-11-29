<template>
  <div class="mt-6 p-6 max-w-[80rem] mx-auto">
    <h1 class="text-2xl font-semibold ">Create Offer</h1>
    <hr class="my-4 border-gray-200 shadow-sm" />

    <OfferForm :initial="{}" :action="route('admin.offers.store')" method="post" />
  </div>
</template>

<script setup>
import OfferForm from '@/Admin/Components/OfferForm.vue';
import {route} from 'ziggy-js';
</script>
