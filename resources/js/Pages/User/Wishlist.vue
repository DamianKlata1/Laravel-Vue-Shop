<template>
    <UserLayout>
        <section class="text-gray-600 body-font relative">
            <div class="container px-5 py-24 mx-auto flex sm:flex-nowrap flex-wrap">
                <div
                    class="lg:w-2/3 md:w-1/2 rounded-lg sm:mr-10 p-10 ">
                    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400"
                               v-if="wishlistItems.length>0">
                            <thead
                                class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="px-16 py-3">
                                    <span class="sr-only">Image</span>
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Product
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Price
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Action
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="wishlistItem in wishlistItems" :key="wishlistItem.id"
                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <td class="p-4">
                                    <img v-if="wishlistItem.product.product_images.length >0"
                                         :src="`/storage/${wishlistItem.product.product_images[0].image}`"
                                         class="w-16 md:w-32 max-w-full max-h-full" alt="product image">
                                    <img v-else src="/icons/image-not-found-icon.png"
                                         class="w-16 md:w-32 max-w-full max-h-full" alt="product image">
                                </td>
                                <td class="px-6 py-4 font-semibold text-gray-900 dark:text-white">
                                    {{ wishlistItem.product.title }}
                                </td>
                                <td class="px-6 py-4 font-semibold text-gray-900 dark:text-white">
                                    ${{ wishlistItem.product.price }}
                                </td>
                                <td class="px-6 py-4">
                                    <button @click="addToCart(wishlistItem.product)"
                                            class="font-medium text-green-600 dark:text-green-500 hover:underline">Add to
                                        cart
                                    </button>
                                </td>
                                <td class="px-6 py-4">
                                    <button @click="viewProduct(wishlistItem.product)"
                                            class="font-medium text-gray-600 dark:text-gray-500 hover:underline">View
                                    </button>
                                </td>
                                <td class="px-6 py-4">
                                    <button @click="remove(wishlistItem)"
                                            class="font-medium text-red-600 dark:text-red-500 hover:underline">Remove
                                    </button>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        <div v-else>
                            No products on wishlist
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </UserLayout>
</template>
<script setup>
import UserLayout from "@/Pages/User/Layouts/UserLayout.vue";
import {router, usePage} from "@inertiajs/vue3";
import Swal from "sweetalert2";

defineProps({
    wishlistItems: {
        type: Array,
        default: []
    }
})


const addToCart = async (product) => {
    try {
        await router.post(route('cart.store', product), null, {
            onSuccess: (page) => {
                if (page.props.flash.success) {
                    Swal.fire({
                        toast: true,
                        icon: 'success',
                        position: 'top-end',
                        showConfirmButton: false,
                        title: page.props.flash.success,
                    })
                }
            }
        })
    } catch (e) {
        console.log(e)
    }

}
const remove = async (wishlistItem) => {
    try{
        await router.delete(route('wishlist.delete',wishlistItem))
    }
    catch (e) {
        console.log(e)
    }
}
const viewProduct = async (product) => {
    try{
        await router.get(route('products.showDetails',product))
    }
    catch (e) {
        console.log(e)
    }
}
</script>
