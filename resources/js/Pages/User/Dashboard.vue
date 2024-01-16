<template>
    <UserLayout>

        <h1 class="text-5xl font-extrabold tracking-tight text-gray-900  items-center max-w-screen-xl mx-auto mt-10">Your Orders</h1>
        <div v-if="orders.length>0" class="relative overflow-x-auto max-w-screen-xl py-12 mx-auto">
            <table v-show="order.order_items.length>0" v-for="order in orders" :key="order.id"
                   class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400 mb-5">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">
                        Order{{ order.id }}
                    </th>
                    <td class="px-6 py-4">
                    </td>
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
                    <td class="px-6 py-4">
                        {{formatDate(order.created_at)}}
                    </td>
                </tr>

                </thead>
                <tbody>
                <tr v-for="order_item in order.order_items" :key="order_item.id"
                    class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                    <th scope="row" class="px-6 py-4 font-medium w-1/5 text-gray-900 whitespace-nowrap dark:text-white">
                        {{ order_item.product.title }}
                    </th>
                    <td class="px-6 py-4">
                    </td>
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

        </div>
        <div class="relative overflow-x-auto max-w-screen-xl py-24 mx-auto" v-else>
            No orders made yet
        </div>
    </UserLayout>
</template>
<script setup>
import UserLayout from './layouts/UserLayout.vue'

defineProps({
    orders: {
        type: Array,
        required: true
    }
})
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

