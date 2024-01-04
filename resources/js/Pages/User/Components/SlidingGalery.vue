<template>


    <div id="gallery" class="relative w-3/4 mx-auto" data-carousel="slide">
        <!-- Carousel wrapper -->
        <div class="relative h-56 overflow-hidden rounded-lg md:h-96">
            <!-- Item 1 -->
            <div v-for="product in products" :key="product.id"
                 class="hidden duration-700 ease-in-out"
                 data-carousel-item
                 @click="router.get(route('products.showDetails',product))">
                <img v-if="product.product_images.length>0" :src="`/${product.product_images[0].image}`"
                     class="absolute block max-w-full h-auto max-w-xl -translate-x-1/2 -translate-y-1/2 top-1/2 left-1/2"
                     alt="product image">
                <img v-else src="/icons/image-not-found-icon.png"
                     class="absolute block max-w-full h-auto  -translate-x-1/2 -translate-y-1/2 top-1/2 left-1/2"
                     alt="product image">
                <div class="absolute bottom-0 left-96 right-96 bg-black bg-opacity-50 p-4 text-white flex flex-col items-center">
                    <p class="text-sm font-semibold ">{{ product.title }}</p>
                    <p class="text-xs ">{{ product.brand.name }}</p>
                    <p class="text-xs ">{{ product.category.name }}</p>
                </div>
            </div>
        </div>
        <!-- Slider controls -->
        <button type="button"
                class="absolute top-0 start-0 z-30 flex items-center justify-center h-full px-4 cursor-pointer group focus:outline-none"
                data-carousel-prev>
        <span
            class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-white/30 dark:bg-gray-800/30 group-hover:bg-white/50 dark:group-hover:bg-gray-800/60 group-focus:ring-4 group-focus:ring-white dark:group-focus:ring-gray-800/70 group-focus:outline-none">
            <svg class="w-4 h-4 text-gray dark:text-gray-800 rtl:rotate-180" aria-hidden="true"
                 xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M5 1 1 5l4 4"/>
            </svg>
            <span class="sr-only">Previous</span>
        </span>
        </button>
        <button type="button"
                class="absolute top-0 end-0 z-30 flex items-center justify-center h-full px-4 cursor-pointer group focus:outline-none"
                data-carousel-next>
        <span
            class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-white/30 dark:bg-gray-800/30 group-hover:bg-white/50 dark:group-hover:bg-gray-800/60 group-focus:ring-4 group-focus:ring-white dark:group-focus:ring-gray-800/70 group-focus:outline-none">
            <svg class="w-4 h-4 text-gray dark:text-gray-800 rtl:rotate-180" aria-hidden="true"
                 xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="m1 9 4-4-4-4"/>
            </svg>
            <span class="sr-only">Next</span>
        </span>
        </button>
    </div>

</template>
<script setup>

import {router} from "@inertiajs/vue3";

defineProps({
    products: {
        type: Array,
        required: true
    }
})


</script>
