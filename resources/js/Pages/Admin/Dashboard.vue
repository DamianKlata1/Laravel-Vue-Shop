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
                        <dt class="mb-2 text-3xl font-extrabold">{{ transactionAmount }}</dt>
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
                        <dt class="mb-2 text-3xl font-extrabold">{{ uniqueVisitorsAmount }}</dt>
                        <dd class="text-gray-500 dark:text-gray-400">Unique visitors</dd>
                    </div>
                </div>
            </div>
            <div class="border-2 border-dashed rounded-lg border-gray-300 dark:border-gray-600 mb-4 ">
                <div
                    class="  max-w-screen-xl py-6 mx-auto   ">
                <form @submit.prevent="search" class="flex items-center">
                    <label for="default-search" class="mb-2 text-sm font-medium text-gray-900 sr-only dark:text-white">Search</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                                 xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                            </svg>
                        </div>
                        <input type="search" v-model="search_input" id="default-search"
                               class="block w-full lg:w-96 p-4 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                               placeholder="Search Order..." required>
                        <button type="submit"
                                class="text-white absolute end-2.5 bottom-2.5 bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                            Search
                        </button>
                    </div>
                </form>
                        </div>
                <div v-if="orders.data.length>0" class="relative overflow-x-auto max-w-screen-xl py-6 mx-auto">
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
                                Price
                            </td>
                            <td class="px-6 py-4">
                                Quantity
                            </td>
                            <td class="px-6 py-4"
                                :class="{'text-green-500': order.status === 'paid',
                                'text-red-500': order.status === 'unpaid' || order.status === 'cancelled' }">
                                Status: {{ order.status }}
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
                                ${{ order_item.product.price }}
                            </td>
                            <td class="px-6 py-4">
                                {{ order_item.quantity }}
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
import {onMounted, ref} from 'vue'
import { initFlowbite } from 'flowbite'
import AdminLayout from './Components/AdminLayout.vue'
import {router, usePage} from "@inertiajs/vue3";
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
    transactionAmount: {
        type: Number,
        required: true
    },
    totalIncome: {
        type: Number,
        required: true
    },
    uniqueVisitorsAmount: {
        type: Number,
        required: true
    },
})

const search_input = ref('');
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
const search = async () => {
    try {
        await router.get(route('admin.dashboard'), {
            search: search_input.value
        }, {
            preserveState: true,
            replace: true
        })
    } catch (e) {
        console.log(e)
    }
}

</script>
