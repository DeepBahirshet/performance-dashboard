<template>
  <div v-if="links.length">
    <nav class="flex gap-2">

      <template v-for="(link, i) in links" :key="i">
        <a
          v-if="link.url"
          :href="link.url"
          v-html="link.label"
          class="px-3 py-1 border rounded"
        ></a>
        <span
          v-else
          class="px-3 py-1 border rounded"
          v-html="link.label"
        ></span>
      </template>

    </nav>
  </div>
</template>

<script setup>
defineProps({
  links: {
    type: Array,
    required: true,
  },
});
</script>
