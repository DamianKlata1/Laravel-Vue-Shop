<template>
    <UserLayout>

        <SlidingGalery :products="products.data" v-if="products.data.length>0"></SlidingGalery>
        <div class="bg-white">
            <div class="mx-auto max-w-2xl px-4 py-16 sm:px-6 sm:py-24 lg:max-w-7xl lg:px-8">
                <h2 class="text-2xl font-bold tracking-tight text-gray-900">Latest products</h2>

                <Products :products="products.data"></Products>
                <div class="flex justify-center mt-5">
                    <Link :href="route('products.list')"
                          class="text-gray-900 bg-gradient-to-r from-teal-200 to-lime-200 hover:bg-gradient-to-l hover:from-teal-200 hover:to-lime-200 focus:ring-4 focus:outline-none focus:ring-lime-200 dark:focus:ring-teal-700 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2">
                        View all products
                    </Link>

                </div>

            </div>
        </div>
    </UserLayout>

</template>
<script setup>
import Products from "@/Pages/User/Components/Products.vue";
import {onMounted} from 'vue'
import {initFlowbite} from 'flowbite'
import {router, Link} from "@inertiajs/vue3";
import Swal from "sweetalert2";
import UserLayout from "./Layouts/UserLayout.vue";
import Hero from "@/Pages/User/Layouts/Hero.vue";
import SlidingGalery from "./Components/SlidingGalery.vue";


// initialize components based on data attribute selectors
onMounted(() => {
    initFlowbite();
})

defineProps({
    products: {
        type: Array,
        required: true
    }
})


</script>
