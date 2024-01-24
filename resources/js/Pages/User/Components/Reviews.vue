<template>

    <section class="overflow-hidden bg-white py-11 font-poppins dark:bg-gray-800 max-w-prose mx-auto">
        <h1 class="text-3xl font-bold mb-4">Reviews</h1>
        <div v-if="reviews.length === 0">
            <p>No reviews yet for this product.</p>
        </div>
        <article v-for="review in reviews">
            <div class="flex items-center mb-4">

                <div class="font-medium dark:text-white">
                    <p>{{ review.user.name }}
                        <time datetime="2014-08-16 19:00" class="block text-sm text-gray-500 dark:text-gray-400">Joined
                            {{ formatDate(review.user.created_at) }}
                        </time>
                    </p>
                </div>
            </div>
            <div class="flex items-center mb-1 space-x-1 rtl:space-x-reverse">
                <svg class="w-4 h-4 ms-1" :class="{
                'text-yellow-300': review.rating > 0,
                'text-gray-300 dark:text-gray-500': review.rating <=0
            }" aria-hidden="true"
                     xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 22 20">
                    <path
                        d="M20.924 7.625a1.523 1.523 0 0 0-1.238-1.044l-5.051-.734-2.259-4.577a1.534 1.534 0 0 0-2.752 0L7.365 5.847l-5.051.734A1.535 1.535 0 0 0 1.463 9.2l3.656 3.563-.863 5.031a1.532 1.532 0 0 0 2.226 1.616L11 17.033l4.518 2.375a1.534 1.534 0 0 0 2.226-1.617l-.863-5.03L20.537 9.2a1.523 1.523 0 0 0 .387-1.575Z"/>
                </svg>
                <svg class="w-4 h-4 ms-1" :class="{
                'text-yellow-300': review.rating > 1,
                'text-gray-300 dark:text-gray-500': review.rating <=1
            }" aria-hidden="true"
                     xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 22 20">
                    <path
                        d="M20.924 7.625a1.523 1.523 0 0 0-1.238-1.044l-5.051-.734-2.259-4.577a1.534 1.534 0 0 0-2.752 0L7.365 5.847l-5.051.734A1.535 1.535 0 0 0 1.463 9.2l3.656 3.563-.863 5.031a1.532 1.532 0 0 0 2.226 1.616L11 17.033l4.518 2.375a1.534 1.534 0 0 0 2.226-1.617l-.863-5.03L20.537 9.2a1.523 1.523 0 0 0 .387-1.575Z"/>
                </svg>
                <svg class="w-4 h-4 ms-1" :class="{
                'text-yellow-300': review.rating > 2,
                'text-gray-300 dark:text-gray-500': review.rating <=2
            }" aria-hidden="true"
                     xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 22 20">
                    <path
                        d="M20.924 7.625a1.523 1.523 0 0 0-1.238-1.044l-5.051-.734-2.259-4.577a1.534 1.534 0 0 0-2.752 0L7.365 5.847l-5.051.734A1.535 1.535 0 0 0 1.463 9.2l3.656 3.563-.863 5.031a1.532 1.532 0 0 0 2.226 1.616L11 17.033l4.518 2.375a1.534 1.534 0 0 0 2.226-1.617l-.863-5.03L20.537 9.2a1.523 1.523 0 0 0 .387-1.575Z"/>
                </svg>
                <svg class="w-4 h-4 ms-1" :class="{
                'text-yellow-300': review.rating > 3,
                'text-gray-300 dark:text-gray-500': review.rating <=3
            }" aria-hidden="true"
                     xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 22 20">
                    <path
                        d="M20.924 7.625a1.523 1.523 0 0 0-1.238-1.044l-5.051-.734-2.259-4.577a1.534 1.534 0 0 0-2.752 0L7.365 5.847l-5.051.734A1.535 1.535 0 0 0 1.463 9.2l3.656 3.563-.863 5.031a1.532 1.532 0 0 0 2.226 1.616L11 17.033l4.518 2.375a1.534 1.534 0 0 0 2.226-1.617l-.863-5.03L20.537 9.2a1.523 1.523 0 0 0 .387-1.575Z"/>
                </svg>
                <svg class="w-4 h-4 ms-1" :class="{
                'text-yellow-300': review.rating > 4,
                'text-gray-300 dark:text-gray-500': review.rating <=4
            }" aria-hidden="true"
                     xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 22 20">
                    <path
                        d="M20.924 7.625a1.523 1.523 0 0 0-1.238-1.044l-5.051-.734-2.259-4.577a1.534 1.534 0 0 0-2.752 0L7.365 5.847l-5.051.734A1.535 1.535 0 0 0 1.463 9.2l3.656 3.563-.863 5.031a1.532 1.532 0 0 0 2.226 1.616L11 17.033l4.518 2.375a1.534 1.534 0 0 0 2.226-1.617l-.863-5.03L20.537 9.2a1.523 1.523 0 0 0 .387-1.575Z"/>
                </svg>

            </div>
            <footer class="mb-5 text-sm text-gray-500 dark:text-gray-400">
                <p>Reviewed in the United Kingdom on
                    <time datetime="{{review.created_at}}">{{ formatDate(review.created_at) }}</time>
                </p>
            </footer>
            <p class="mb-2 text-gray-500 dark:text-gray-400">
                {{ review.comment }}
            </p>

            <a href="#" class="block mb-5 text-sm font-medium text-blue-600 hover:underline dark:text-blue-500">Read
                more</a>
            <aside>
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                    {{
                        checkIfUserMarkedAsHelpful(review, auth.user) ? 'You and ' : ''
                    }}
                    {{
                        checkIfUserMarkedAsHelpful(review, auth.user) ? review.helpful_count - 1 : review.helpful_count
                    }} people found this helpful
                </p>
                <div class="flex items-center mt-3">
                    <button @click="toggleMarkAsHelpful(review)"
                            class="text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-200 font-medium rounded-lg text-xs px-2 py-1.5 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700">
                        {{ !checkIfUserMarkedAsHelpful(review, auth.user) ? 'Helpful' : 'Unmark as helpful' }}
                    </button>
                    <a href="#"
                       class="ps-4 text-sm font-medium text-blue-600 hover:underline dark:text-blue-500 border-gray-200 ms-4 border-s md:mb-0 dark:border-gray-600">Report
                        abuse</a>
                </div>
            </aside>
        </article>
    </section>
</template>

<script setup>

import {ref} from 'vue';
import {formatDate} from "@/Helpers/dateFormatter.js";
import {Link, usePage} from '@inertiajs/vue3'
import {router} from "@inertiajs/vue3";
import {displayAllNotifications} from "@/Helpers/notification.js";

const auth = usePage().props.auth;

defineProps({
    reviews: {
        type: Array,
        required: true
    }
})
const comment = ref('');

const toggleMarkAsHelpful = async (review) => {
    try {
        await router.post(`/reviews/${review.id}/helpful-toggle`, {}, {
            preserveScroll: true,
            preserveState: true,
            onSuccess: page => {
                displayAllNotifications(page);
            }
        })
    } catch (e) {
        displayAllNotifications(page);
    }
}
const checkIfUserMarkedAsHelpful = (review, user) => {
    if (!user) return false;
    return review.helpful_users.some(helpful_user => helpful_user.id === user.id);
}

</script>
