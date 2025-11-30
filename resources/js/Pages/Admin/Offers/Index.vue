<template>
    <div class="flex justify-center mt-6">
        <div class="p-6">
            <h1 class="text-2xl font-semibold">Offers</h1>
            <hr class="my-4 border-gray-200 shadow-sm" />

            <div class="flex justify-between mt-4">
                <input
                    v-model="filters.q"
                    @keyup="search"
                    placeholder="Search offers..."
                    class="w-[20%] max-w-sm px-4 py-2 rounded-xl border border-gray-300 shadow-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all duration-200 bg-white/80 backdrop-blur-sm mb-4"
                />

                <div class="flex items-center justify-between mb-4">
                    <Link
                        :href="route('admin.offers.create')"
                        class="ml-2 bg-emerald-600 px-4 py-2 text-white rounded-lg hover:bg-emerald-700 transition"
                    >
                        New Offer
                    </Link>
                </div>
            </div>

            <table class="bg-white rounded-xl shadow-lg p-4 w-[80rem]">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="py-3 px-4 font-semibold text-center">
                            Name
                        </th>
                        <th class="py-3 px-4 font-semibold text-center">
                            Code
                        </th>
                        <th class="py-3 px-4 font-semibold text-center">
                            Start Date
                        </th>
                        <th class="py-3 px-4 font-semibold text-center">
                            Expire Date
                        </th>
                        <th class="py-3 px-4 font-semibold text-center">
                            Budget
                        </th>
                        <th class="py-3 px-4 font-semibold text-center">
                            Max Redemptions
                        </th>
                        <th class="py-3 px-4 font-semibold text-center">
                            Actions
                        </th>
                    </tr>
                </thead>

                <tbody>
                    <tr
                        v-for="offer in offers.data"
                        :key="offer.id"
                        class="hover:bg-gray-50"
                    >
                        <td class="py-3 px-4 text-center">{{ offer.name }}</td>
                        <td class="py-3 px-4 text-center">
                            {{ offer.offer_code }}
                        </td>
                        <td class="py-3 px-4 text-center">
                            {{ formatDate(offer.start_date) }}
                        </td>
                        <td class="py-3 px-4 text-center">
                            {{ formatDate(offer.end_date) }}
                        </td>
                        <td class="py-3 px-4 text-center">
                            {{ formatCurrency(offer.budget) }}
                        </td>
                        <td class="py-3 px-4 text-center">
                            {{ offer.max_redemptions ?? 0 }}
                        </td>
                        <td class="py-3 px-4 text-center">
                            <Link
                                :href="route('admin.offers.edit', offer.id)" title="Edit"
                                class="inline-flex items-center justify-center w-10 h-10 bg-sky-600 text-white rounded-lg hover:bg-sky-700 transition mr-3 cursor-pointer">
                                <PencilSquareIcon class="w-5 h-5" />
                            </Link>

                            <Link
                                :href="route('admin.offers.dashboard', offer.id)"
                                class="inline-flex items-center justify-center w-10 h-10 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition mr-3 cursor-pointer"
                                title="View dashboard"
                            >
                                <EyeIcon class="w-5 h-5" />
                            </Link>

                            <form
                                :action="
                                    route('admin.offers.destroy', offer.id)
                                "
                                method="post"
                                @submit.prevent="openDeleteModal(offer.id)"
                                class="inline"
                            >
                                <button
                                    type="submit" title="Delete"
                                    class="bg-red-500 inline-flex items-center justify-center w-10 h-10 text-white rounded-lg hover:bg-red-600 transition-all duration-200 cursor-pointer"
                                >
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

    <!-- Delete Confirmation Modal -->
    <div
        v-if="showDeleteModal"
        class="fixed inset-0 backdrop-blur-sm bg-black/20 flex items-center justify-center z-50"
    >
        <div class="bg-white p-6 rounded-xl shadow-xl w-80">
            <h2 class="text-lg font-semibold mb-3">Delete Offer</h2>
            <p class="text-gray-600 mb-6">
                Are you sure you want to delete this offer?
            </p>

            <div class="flex justify-end gap-3">
                <button
                    @click="showDeleteModal = false"
                    class="px-4 py-2 rounded-lg bg-gray-200 hover:bg-gray-300 transition cursor-pointer"
                >
                    Cancel
                </button>

                <button
                    @click="destroy"
                    class="px-4 py-2 rounded-lg bg-red-500 text-white hover:bg-red-600 transition cursor-pointer"
                >
                    Delete
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from "vue";
import { Link, usePage, useForm } from '@inertiajs/vue3';
import { router } from "@inertiajs/core";
import { route } from "ziggy-js";
import Pagination from "../../Shared/Pagination.vue";
import { PencilSquareIcon, EyeIcon, TrashIcon } from "@heroicons/vue/24/solid";

const props = defineProps({
    offers: Object,
    filters: Object,
});

const showDeleteModal = ref(false);
const deleteId = ref(null);

function openDeleteModal(id) {
    deleteId.value = id;
    showDeleteModal.value = true;
}

const deleteForm = useForm({});
const destroy = () => {
  deleteForm.delete(route('admin.offers.destroy', deleteId.value), {
    onSuccess: () => {
      showDeleteModal.value = false;
    },
  });
};

const page = usePage();
const filters = ref(props.filters ?? { q: "" });

function search() {
    router.get(
        route("admin.offers.index"),
        { q: filters.value.q },
        { preserveState: true, replace: true }
    );
}

function formatDate(date) {
    if (!date) return "-";
    return new Date(date).toLocaleDateString();
}
function formatCurrency(val) {
    return Number(val).toLocaleString(undefined, {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
    });
}
</script>

<style scoped></style>
