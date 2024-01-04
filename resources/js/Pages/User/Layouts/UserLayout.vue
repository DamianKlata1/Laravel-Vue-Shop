<template>


    <Header/>

    <slot/>

    <Footer/>


</template>
<script setup>
import {onMounted} from 'vue'
import {initFlowbite} from 'flowbite'
import Header from './Header.vue'
import Hero from './Hero.vue'
import Footer from './Footer.vue'

// initialize components based on data attribute selectors
onMounted(() => {
    initFlowbite();
})


</script>
