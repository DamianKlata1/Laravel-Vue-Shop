<template>
    <div class="mt-6 grid grid-cols-1 gap-x-6 gap-y-10 sm:grid-cols-2 lg:grid-cols-4 xl:gap-x-8">
        <div v-for="product in products" :key="product.id" class="group relative">
            <div
                class="aspect-h-1 aspect-w-1 w-full overflow-hidden rounded-md bg-gray-200 lg:aspect-none group-hover:opacity-75 lg:h-80">

                <img v-if="product.product_images.length > 0" :src="`/storage/${product.product_images[0].image}`"
                     alt="should be product image"
                     class="h-full w-full object-cover object-center lg:h-full lg:w-full"/>

                <img v-else src="/icons/image-not-found-icon.png" alt="should be product not available"
                     class="h-full w-full object-cover object-center lg:h-full lg:w-full"/>
            </div>
            <div
                class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 cursor-pointer">
                <div class="bg-blue-700 p-2 rounded-full">
                    <a @click="addToCart(product)">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                             stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-white">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/>
                        </svg>
                    </a>

                </div>
                <div class="bg-blue-700 p-2 rounded-full ml-2">
                    <a @click="showDetails(product)">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                             stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-white">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/>
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </a>

                </div>
            </div>
            <div class=" mt-4 flex justify-between">
                <div>
                    <h3 class="text-sm text-gray-700">
                        <a :href="product.href">
                            <span aria-hidden="true" class=""/>
                            {{ product.title }}
                        </a>
                    </h3>
                    <p class="mt-1 text-sm text-gray-500">{{ product.brand.name }}</p>
                    <p class="mt-1 text-sm text-gray-500">{{ product.category.name }}</p>
                </div>
                <div class="flex flex-col items-end">
                    <p class="text-sm font-medium text-gray-900">${{ product.price }}</p>
                    <div class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-500" viewBox="0 0 20 20"
                             fill="currentColor">
                            <path fill-rule="evenodd"
                                  d="M10 18a1.5 1.5 0 0 1-.943-.344l-4.975-4.475A4.287 4.287 0 0 1 2 9.246C2 7.183 3.456 6 5.5 6c1.24 0 2.29.583 3 1.497C8.21 6.583 9.26 6 10.5 6c2.044 0 3.5 1.183 3.5 3.246 0 1.068-.571 2.125-1.082 2.935L10.943 17.63A1.5 1.5 0 0 1 10 18zm-4-6a2 2 0 1 0 4 0h-1a1 1 0 0 1-2 0H6zm6 0a2 2 0 1 0 4 0h-1a1 1 0 0 1-2 0h-1z"
                                  clip-rule="evenodd"/>
                        </svg>
                        <span class="text-sm text-gray-500 ml-1">{{ product.wishlisted_count }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script setup>
import {router} from "@inertiajs/vue3";
import Swal from "sweetalert2";


defineProps({
    products: {
        type: Array,
        required: true
    }
})
const addToCart = async (product) => {
    try {
        await router.post(route('cart.store', product),null, {
            onSuccess: (page) => {
                if (page.props.flash.success) {
                    Swal.fire({
                        toast: true,
                        icon: 'success',
                        position: 'top-end',
                        timer: 3000,
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

const showDetails = async (product) => {
    try {
        router.get(route('products.showDetails', product))
    } catch (e) {
        console.log(e)
    }
}

</script>
