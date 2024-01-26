<template>
    <form @submit.prevent="submitReview(product)" class="flex flex-col space-y-4 max-w-prose mx-auto" >
        <h2 class="text-2xl font-bold text-center mb-4">We value your feedback! Please leave a review.</h2>
        <div class="flex items-center">
            <svg class="w-4 h-4 ms-1" :class="{
                'text-yellow-300': rating > 0,
                'text-gray-300 dark:text-gray-500': rating <=0
            }"
                 @click="rating = 1"
                 aria-hidden="true"
                 xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 22 20">
                <path
                    d="M20.924 7.625a1.523 1.523 0 0 0-1.238-1.044l-5.051-.734-2.259-4.577a1.534 1.534 0 0 0-2.752 0L7.365 5.847l-5.051.734A1.535 1.535 0 0 0 1.463 9.2l3.656 3.563-.863 5.031a1.532 1.532 0 0 0 2.226 1.616L11 17.033l4.518 2.375a1.534 1.534 0 0 0 2.226-1.617l-.863-5.03L20.537 9.2a1.523 1.523 0 0 0 .387-1.575Z"/>
            </svg>
            <svg class="w-4 h-4 ms-1" :class="{
                'text-yellow-300': rating > 1,
                'text-gray-300 dark:text-gray-500': rating <=1
            }"
                 @click="rating = 2"
                 aria-hidden="true"
                 xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 22 20">
                <path
                    d="M20.924 7.625a1.523 1.523 0 0 0-1.238-1.044l-5.051-.734-2.259-4.577a1.534 1.534 0 0 0-2.752 0L7.365 5.847l-5.051.734A1.535 1.535 0 0 0 1.463 9.2l3.656 3.563-.863 5.031a1.532 1.532 0 0 0 2.226 1.616L11 17.033l4.518 2.375a1.534 1.534 0 0 0 2.226-1.617l-.863-5.03L20.537 9.2a1.523 1.523 0 0 0 .387-1.575Z"/>
            </svg>
            <svg class="w-4 h-4 ms-1" :class="{
                'text-yellow-300': rating > 2,
                'text-gray-300 dark:text-gray-500': rating <=2
            }"
                 @click="rating = 3"
                 aria-hidden="true"
                 xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 22 20">
                <path
                    d="M20.924 7.625a1.523 1.523 0 0 0-1.238-1.044l-5.051-.734-2.259-4.577a1.534 1.534 0 0 0-2.752 0L7.365 5.847l-5.051.734A1.535 1.535 0 0 0 1.463 9.2l3.656 3.563-.863 5.031a1.532 1.532 0 0 0 2.226 1.616L11 17.033l4.518 2.375a1.534 1.534 0 0 0 2.226-1.617l-.863-5.03L20.537 9.2a1.523 1.523 0 0 0 .387-1.575Z"/>
            </svg>
            <svg class="w-4 h-4 ms-1" :class="{
                'text-yellow-300': rating > 3,
                'text-gray-300 dark:text-gray-500': rating <=3
            }"
                 @click="rating = 4"
                 aria-hidden="true"
                 xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 22 20">
                <path
                    d="M20.924 7.625a1.523 1.523 0 0 0-1.238-1.044l-5.051-.734-2.259-4.577a1.534 1.534 0 0 0-2.752 0L7.365 5.847l-5.051.734A1.535 1.535 0 0 0 1.463 9.2l3.656 3.563-.863 5.031a1.532 1.532 0 0 0 2.226 1.616L11 17.033l4.518 2.375a1.534 1.534 0 0 0 2.226-1.617l-.863-5.03L20.537 9.2a1.523 1.523 0 0 0 .387-1.575Z"/>
            </svg>
            <svg class="w-4 h-4 ms-1" :class="{
                'text-yellow-300': rating > 4,
                'text-gray-300 dark:text-gray-500': rating <=4
            }"
                 @click="rating = 5"
                 aria-hidden="true"
                 xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 22 20">
                <path
                    d="M20.924 7.625a1.523 1.523 0 0 0-1.238-1.044l-5.051-.734-2.259-4.577a1.534 1.534 0 0 0-2.752 0L7.365 5.847l-5.051.734A1.535 1.535 0 0 0 1.463 9.2l3.656 3.563-.863 5.031a1.532 1.532 0 0 0 2.226 1.616L11 17.033l4.518 2.375a1.534 1.534 0 0 0 2.226-1.617l-.863-5.03L20.537 9.2a1.523 1.523 0 0 0 .387-1.575Z"/>
            </svg>
        </div>
        <div>
            <label for="comment" class="text-sm font-bold mb-1">Comment:</label>
            <textarea id="comment" v-model="comment" required class="border rounded p-2 w-full"></textarea>
        </div>
        <button type="submit" class="bg-blue-500 text-white rounded p-2 w-full">Submit Review</button>
    </form>
</template>

<script setup>
import {ref} from 'vue';
import {router} from "@inertiajs/vue3";
import {displayAllNotifications} from "@/Helpers/notification.js";

defineProps({
    product: {
        type: Object,
        required: true
    }
});
const rating = ref(0);
const comment = ref('');




const submitReview = async (product) => {
    try{
        const data = {
            rating: rating.value,
            comment: comment.value
        };
        await router.post(`/products/${product.id}/reviews`, data,{
            preserveState: true,
            onSuccess: (page) => {
                displayAllNotifications(page)
                resetForm();
            }
        });
    }
    catch (e) {
        console.log(e);
    }

}
function resetForm() {
    rating.value = 0;
    comment.value = '';
}

</script>



