<template>
    <AdminLayout>
        <div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
                <div class="border-2 border-dashed border-gray-300 rounded-lg dark:border-gray-600 h-16 md:h-32 flex items-center justify-center">
                    <div class="flex flex-col items-center justify-center">
                        <dt class="mb-2 text-3xl font-extrabold">{{ usersAmount }}</dt>
                        <dd class="text-gray-500 dark:text-gray-400">Users</dd>
                    </div>
                </div>

                <div class="border-2 border-dashed border-gray-300 rounded-lg dark:border-gray-600 h-16 md:h-32 flex items-center justify-center">
                    <div class="flex flex-col items-center justify-center">
                        <dt class="mb-2 text-3xl font-extrabold">{{ orders.data.length }}</dt>
                        <dd class="text-gray-500 dark:text-gray-400">Transactions</dd>
                    </div>
                </div>
                <div class="border-2 border-dashed border-gray-300 rounded-lg dark:border-gray-600 h-16 md:h-32 flex items-center justify-center">
                    <div class="flex flex-col items-center justify-center">
                        <dt class="mb-2 text-3xl font-extrabold">${{ totalIncome }}</dt>
                        <dd class="text-gray-500 dark:text-gray-400">Total income</dd>
                    </div>
                </div>
                <div class="border-2 border-dashed border-gray-300 rounded-lg dark:border-gray-600 h-16 md:h-32 flex items-center justify-center">
                    <div class="flex flex-col items-center justify-center">
                        <dt class="mb-2 text-3xl font-extrabold">{{ }}</dt>
                        <dd class="text-gray-500 dark:text-gray-400">Unique visitors</dd>
                    </div>
                </div>
            </div>
            <div class="border-2 border-dashed rounded-lg border-gray-300 dark:border-gray-600 mb-4">
                <div v-if="orders.data.length>0" class="relative overflow-x-auto max-w-screen-xl py-24 mx-auto">
                    <table v-for="order in orders.data" :key="order.id"
                           class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400 mb-5">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-3">
                                Order{{ order.id }}
                            </th>
                            <td class="px-6 py-4">
                                Brand
                            </td>
                            <td class="px-6 py-4">
                                Category
                            </td>
                            <td class="px-6 py-4">
                                Quantity
                            </td>
                            <td class="px-6 py-4">
                                Price
                            </td>
                            <td class="px-6 py-4">
                                {{formatDate(order.created_at)}}
                            </td>
                            <td class="px-6 py-4">
                                {{order.created_by.name}}
                            </td>
                        </tr>

                        </thead>
                        <tbody>
                        <tr v-for="order_item in order.order_items" :key="order_item.id"
                            class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                            <th scope="row" class="px-6 py-4 font-medium w-1/5cd  text-gray-900 whitespace-nowrap dark:text-white">
                                {{ order_item.product.title }}
                            </th>
                            <td class="px-6 py-4">
                                {{ order_item.product.brand.name }}
                            </td>
                            <td class="px-6 py-4">
                                {{ order_item.product.category.name }}
                            </td>
                            <td class="px-6 py-4">
                                {{ order_item.quantity }}
                            </td>

                            <td class="px-6 py-4">
                                ${{ order_item.product.price }}
                            </td>

                        </tr>
                        <td class="px-6 py-4">

                        </td>
                        <td class="px-6 py-4">

                        </td>
                        <td class="px-6 py-4">

                        </td>
                        <td class="px-6 py-4">

                        </td>
                        <td class="px-6 py-4 bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                           ${{ order.price }} Total
                        </td>

                        </tbody>
                    </table>
                    <Pagination :links="orders.links"/>
                </div>
                <div class="relative overflow-x-auto max-w-screen-xl py-24 mx-auto" v-else>
                    No orders made yet
                </div>
            </div>


            <div class="grid grid-cols-2 gap-4 mb-4">
                <div class="border-2 border-dashed rounded-lg border-gray-300 dark:border-gray-600 h-48 md:h-72"></div>
                <div class="border-2 border-dashed rounded-lg border-gray-300 dark:border-gray-600 h-48 md:h-72"></div>
                <div class="border-2 border-dashed rounded-lg border-gray-300 dark:border-gray-600 h-48 md:h-72"></div>
                <div class="border-2 border-dashed rounded-lg border-gray-300 dark:border-gray-600 h-48 md:h-72"></div>
            </div>
            <div class="border-2 border-dashed rounded-lg border-gray-300 dark:border-gray-600 h-96 mb-4"></div>
            <div class="grid grid-cols-2 gap-4">
                <div class="border-2 border-dashed rounded-lg border-gray-300 dark:border-gray-600 h-48 md:h-72"></div>
                <div class="border-2 border-dashed rounded-lg border-gray-300 dark:border-gray-600 h-48 md:h-72"></div>
                <div class="border-2 border-dashed rounded-lg border-gray-300 dark:border-gray-600 h-48 md:h-72"></div>
                <div class="border-2 border-dashed rounded-lg border-gray-300 dark:border-gray-600 h-48 md:h-72"></div>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import Pagination from "@/Components/Pagination.vue";
import { onMounted } from 'vue'
import { initFlowbite } from 'flowbite'
import AdminLayout from './Components/AdminLayout.vue'
import {usePage} from "@inertiajs/vue3";
// initialize components based on data attribute selectors
onMounted(() => {
    initFlowbite();
})
defineProps({
    orders: {
        type: Array,
        required: true
    },
    usersAmount: {
        type: Number,
        required: true
    },
    productsAmount: {
        type: Number,
        required: true
    },
})
const totalIncome = usePage().props.totalIncome
function formatDate(timestamp) {
    const date = new Date(timestamp);
    const options = {
        year: 'numeric',
        month: 'numeric',
        day: 'numeric',
        hour: 'numeric',
        minute: 'numeric',
        second: 'numeric',
        hour12: false // Use 24-hour format
    };
    return date.toLocaleDateString(undefined, options); // Adjust the format as needed
}

</script>
