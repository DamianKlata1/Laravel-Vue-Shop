<template>
    <AdminLayout>
        <ProductList :products = "products"/>
    </AdminLayout>
</template>

<script setup>
defineProps({
    products: {
        type: Array,
        required: true
    }
});

import AdminLayout from "../Components/AdminLayout.vue";
import ProductList from "./ProductList.vue";
</script>
