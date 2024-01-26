<template>
    <UserLayout>
        <section class="overflow-hidden bg-white py-11 font-poppins dark:bg-gray-800">
            <div class="max-w-6xl px-4 py-4 mx-auto lg:py-8 md:px-6">
                <div class="flex flex-wrap -mx-4">
                    <div class="w-full px-4 md:w-1/2 ">
                        <div class="sticky top-0 z-50 overflow-hidden ">
                            <div class="relative mb-6 lg:mb-10 lg:h-2/4 ">
                                <img v-if="product.data.product_images.length>0"
                                     :src="selectedImageSrc ? `/${selectedImageSrc}` : `/${product.data.product_images[0].image}`"
                                     alt=""
                                     class="object-cover w-full lg:h-full ">
                                <img v-else
                                     src="/icons/image-not-found-icon.png"
                                     alt=""
                                     class="object-cover w-full lg:h-full ">
                            </div>
                            <div class="flex-wrap hidden md:flex">
                                <div class="w-1/2 p-2 sm:w-1/4" v-for="product_image in product.data.product_images">
                                    <a @click="selectImage(product_image.image)"
                                       class="block border border-blue-300 dark:border-transparent dark:hover:border-blue-300 hover:border-blue-300">
                                        <img :src="`/${product_image.image}`" alt="product image"
                                             class="object-cover w-full lg:h-20">
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="w-full px-4 md:w-1/2 ">
                        <div class="lg:pl-20">
                            <div class="mb-8 ">
                                <span class="text-lg font-medium text-rose-500 dark:text-rose-200"
                                      v-if="isNewProduct(product.data.created_at)">New</span>
                                <h2 class="max-w-xl mt-2 mb-6 text-2xl font-bold dark:text-gray-400 md:text-4xl">
                                    {{ product.data.title }}</h2>
                                <p class="max-w-md mb-8 text-gray-700 dark:text-gray-400 my-4">
                                    {{
                                        product.data.description
                                    }}
                                </p>
                                <div class="flex items-center my-4">
                                    <svg class="w-4 h-4 ms-1" :class="{
                'text-yellow-300': product.data.rating > 0,
                'text-gray-300 dark:text-gray-500': product.data.rating <=0
            }" aria-hidden="true"
                                         xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 22 20">
                                        <path
                                            d="M20.924 7.625a1.523 1.523 0 0 0-1.238-1.044l-5.051-.734-2.259-4.577a1.534 1.534 0 0 0-2.752 0L7.365 5.847l-5.051.734A1.535 1.535 0 0 0 1.463 9.2l3.656 3.563-.863 5.031a1.532 1.532 0 0 0 2.226 1.616L11 17.033l4.518 2.375a1.534 1.534 0 0 0 2.226-1.617l-.863-5.03L20.537 9.2a1.523 1.523 0 0 0 .387-1.575Z"/>
                                    </svg>
                                    <svg class="w-4 h-4 ms-1" :class="{
                'text-yellow-300': product.data.rating > 1,
                'text-gray-300 dark:text-gray-500': product.data.rating <=1
            }" aria-hidden="true"
                                         xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 22 20">
                                        <path
                                            d="M20.924 7.625a1.523 1.523 0 0 0-1.238-1.044l-5.051-.734-2.259-4.577a1.534 1.534 0 0 0-2.752 0L7.365 5.847l-5.051.734A1.535 1.535 0 0 0 1.463 9.2l3.656 3.563-.863 5.031a1.532 1.532 0 0 0 2.226 1.616L11 17.033l4.518 2.375a1.534 1.534 0 0 0 2.226-1.617l-.863-5.03L20.537 9.2a1.523 1.523 0 0 0 .387-1.575Z"/>
                                    </svg>
                                    <svg class="w-4 h-4 ms-1" :class="{
                'text-yellow-300': product.data.rating > 2,
                'text-gray-300 dark:text-gray-500': product.data.rating <=2
            }" aria-hidden="true"
                                         xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 22 20">
                                        <path
                                            d="M20.924 7.625a1.523 1.523 0 0 0-1.238-1.044l-5.051-.734-2.259-4.577a1.534 1.534 0 0 0-2.752 0L7.365 5.847l-5.051.734A1.535 1.535 0 0 0 1.463 9.2l3.656 3.563-.863 5.031a1.532 1.532 0 0 0 2.226 1.616L11 17.033l4.518 2.375a1.534 1.534 0 0 0 2.226-1.617l-.863-5.03L20.537 9.2a1.523 1.523 0 0 0 .387-1.575Z"/>
                                    </svg>
                                    <svg class="w-4 h-4 ms-1" :class="{
                'text-yellow-300': product.data.rating > 3,
                'text-gray-300 dark:text-gray-500': product.data.rating <=3
            }" aria-hidden="true"
                                         xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 22 20">
                                        <path
                                            d="M20.924 7.625a1.523 1.523 0 0 0-1.238-1.044l-5.051-.734-2.259-4.577a1.534 1.534 0 0 0-2.752 0L7.365 5.847l-5.051.734A1.535 1.535 0 0 0 1.463 9.2l3.656 3.563-.863 5.031a1.532 1.532 0 0 0 2.226 1.616L11 17.033l4.518 2.375a1.534 1.534 0 0 0 2.226-1.617l-.863-5.03L20.537 9.2a1.523 1.523 0 0 0 .387-1.575Z"/>
                                    </svg>
                                    <svg class="w-4 h-4 ms-1" :class="{
                'text-yellow-300': product.data.rating > 4,
                'text-gray-300 dark:text-gray-500': product.data.rating <=4
            }" aria-hidden="true"
                                         xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 22 20">
                                        <path
                                            d="M20.924 7.625a1.523 1.523 0 0 0-1.238-1.044l-5.051-.734-2.259-4.577a1.534 1.534 0 0 0-2.752 0L7.365 5.847l-5.051.734A1.535 1.535 0 0 0 1.463 9.2l3.656 3.563-.863 5.031a1.532 1.532 0 0 0 2.226 1.616L11 17.033l4.518 2.375a1.534 1.534 0 0 0 2.226-1.617l-.863-5.03L20.537 9.2a1.523 1.523 0 0 0 .387-1.575Z"/>
                                    </svg>
                                    <p class="ms-1 text-sm font-medium text-gray-500 dark:text-gray-400">{{ parseFloat(product.data.rating).toFixed(2) }}</p>
                                    <p class="ms-1 text-sm font-medium text-gray-500 dark:text-gray-400">out of</p>
                                    <p class="ms-1 text-sm font-medium text-gray-500 dark:text-gray-400">{{reviews.data.length}}</p>
                                </div>

                                <p class="inline-block mb-8 text-4xl font-bold text-gray-700 dark:text-gray-400 ">
                                    <span>${{ product.data.price }}</span>
                                    <!--                                    <span-->
                                    <!--                                        class="text-base font-normal text-gray-500 line-through dark:text-gray-400">$1500.99</span>-->
                                </p>
                                <p class="text-green-600 dark:text-green-300" v-if="product.data.quantity > 0">
                                    available</p>
                                <p class="text-red-600 dark:text-red-300" v-else> not available</p>
                            </div>
                            <!--                            <div class="flex items-center mb-8">-->
                            <!--                                <h2 class="w-16 mr-6 text-xl font-bold dark:text-gray-400">-->
                            <!--                                    Colors:</h2>-->
                            <!--                                <div class="flex flex-wrap -mx-2 -mb-2">-->
                            <!--                                    <button-->
                            <!--                                        class="p-1 mb-2 mr-2 border border-transparent hover:border-blue-400 dark:border-gray-800 dark:hover:border-gray-400 ">-->
                            <!--                                        <div class="w-6 h-6 bg-cyan-300"></div>-->
                            <!--                                    </button>-->
                            <!--                                    <button-->
                            <!--                                        class="p-1 mb-2 mr-2 border border-transparent hover:border-blue-400 dark:border-gray-800 dark:hover:border-gray-400">-->
                            <!--                                        <div class="w-6 h-6 bg-green-300 "></div>-->
                            <!--                                    </button>-->
                            <!--                                    <button-->
                            <!--                                        class="p-1 mb-2 border border-transparent hover:border-blue-400 dark:border-gray-800 dark:hover:border-gray-400">-->
                            <!--                                        <div class="w-6 h-6 bg-red-200 "></div>-->
                            <!--                                    </button>-->
                            <!--                                </div>-->
                            <!--                            </div>-->
                            <!--                            <div class="flex items-center mb-8">-->
                            <!--                                <h2 class="w-16 text-xl font-bold dark:text-gray-400">-->
                            <!--                                    Size:</h2>-->
                            <!--                                <div class="flex flex-wrap -mx-2 -mb-2">-->
                            <!--                                    <button-->
                            <!--                                        class="py-1 mb-2 mr-1 border w-11 hover:border-blue-400 dark:border-gray-400 hover:text-blue-600 dark:hover:border-gray-300 dark:text-gray-400">-->
                            <!--                                        XL-->
                            <!--                                    </button>-->
                            <!--                                    <button-->
                            <!--                                        class="py-1 mb-2 mr-1 border w-11 hover:border-blue-400 hover:text-blue-600 dark:border-gray-400 dark:hover:border-gray-300 dark:text-gray-400">-->
                            <!--                                        S-->
                            <!--                                    </button>-->
                            <!--                                    <button-->
                            <!--                                        class="py-1 mb-2 mr-1 border w-11 hover:border-blue-400 hover:text-blue-600 dark:border-gray-400 dark:hover:border-gray-300 dark:text-gray-400">-->
                            <!--                                        M-->
                            <!--                                    </button>-->
                            <!--                                    <button-->
                            <!--                                        class="py-1 mb-2 mr-1 border w-11 hover:border-blue-400 hover:text-blue-600 dark:border-gray-400 dark:hover:border-gray-300 dark:text-gray-400">-->
                            <!--                                        XS-->
                            <!--                                    </button>-->
                            <!--                                </div>-->
                            <!--                            </div>-->
                            <div class="w-32 mb-8 ">
                                <label for=""
                                       class="w-full text-xl font-semibold text-gray-700 dark:text-gray-400">Quantity</label>
                                <div class="relative flex flex-row w-full h-10 mt-4 bg-transparent rounded-lg">
                                    <button @click="decrementQuantity" :disabled="quantity <= 1"
                                            class="w-20 h-full text-gray-600 bg-gray-300 rounded-l outline-none cursor-pointer dark:hover:bg-gray-700 dark:text-gray-400 hover:text-gray-700 dark:bg-gray-900 hover:bg-gray-400">
                                        <span class="m-auto text-2xl font-thin">-</span>
                                    </button>
                                    <input type="number" v-model="quantity" min="1"
                                           class="flex items-center w-full font-semibold text-center text-gray-700 placeholder-gray-700 bg-gray-300 outline-none dark:text-gray-400 dark:placeholder-gray-400 dark:bg-gray-900 focus:outline-none text-md hover:text-black"
                                           placeholder="1">
                                    <button @click="incrementQuantity"
                                            class="w-20 h-full text-gray-600 bg-gray-300 rounded-r outline-none cursor-pointer dark:hover:bg-gray-700 dark:text-gray-400 dark:bg-gray-900 hover:text-gray-700 hover:bg-gray-400">
                                        <span class="m-auto text-2xl font-thin">+</span>
                                    </button>
                                </div>
                            </div>
                            <div class="flex flex-wrap items-center -mx-4 ">
                                <div class="w-full px-4 mb-4 lg:w-1/2 lg:mb-0">
                                    <button @click="addToCart(product.data)"
                                            class="flex items-center justify-center w-full p-4 text-blue-500 border border-blue-500 rounded-md dark:text-gray-200 dark:border-blue-600 hover:bg-blue-600 hover:border-blue-600 hover:text-gray-100 dark:bg-blue-600 dark:hover:bg-blue-700 dark:hover:border-blue-700 dark:hover:text-gray-300">
                                        Add to Cart
                                    </button>
                                </div>
                                <div class="w-full px-4 mb-4 lg:mb-0 lg:w-1/2">
                                    <button @click="addToWishlist(product.data)"
                                            class="flex items-center justify-center w-full p-4 text-blue-500 border border-blue-500 rounded-md dark:text-gray-200 dark:border-blue-600 hover:bg-blue-600 hover:border-blue-600 hover:text-gray-100 dark:bg-blue-600 dark:hover:bg-blue-700 dark:hover:border-blue-700 dark:hover:text-gray-300">
                                        Add to wishlist
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


            </div>
            <ReviewForm :product="product.data" v-if="!checkIfUserHasReviewedProduct(reviews.data)" ></ReviewForm>
            <h2 class="text-2xl font-bold text-center mb-4" v-else>Thank you for your feedback.</h2>

            <div class="overflow-hidden bg-white pt-20 font-poppins dark:bg-gray-800 max-w-prose mx-auto">
                <Pagination :links="reviews.meta.links"></Pagination>
            </div>
            <Reviews :reviews="reviews.data"></Reviews>
        </section>

    </UserLayout>
</template>
<script setup>
import UserLayout from "@/Pages/User/Layouts/UserLayout.vue";
import Reviews from "@/Pages/User/Components/Reviews.vue";
import ReviewForm from "@/Pages/User/Components/ReviewForm.vue";
import {router, usePage} from "@inertiajs/vue3";
import Swal from "sweetalert2";
import {ref, watch} from "vue";
import {displayAllNotifications} from "@/Helpers/notification.js";
import Pagination from "@/Components/Pagination.vue";


defineProps({
    product: {
        type: Object,
        required: true
    },
    reviews: {
        type: Array,
        required: true
    },
    auth: {
        type: Object,
        required: true
    }
})



const quantity = ref(1);
const selectedImageSrc = ref(null);

watch(quantity, (newQuantity, oldQuantity) => {
    if (newQuantity < 1) {
        quantity.value = 1;
    }
    if (newQuantity > usePage().props.product.data.quantity) {
        quantity.value = usePage().props.product.data.quantity;
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'There is not enough quantity in stock!',
        })
    }
})

const incrementQuantity = () => {
    quantity.value++;
}
const decrementQuantity = () => {
    if (quantity.value > 1) {
        quantity.value--;
    }
}

const isNewProduct = (createdAt) => {
    const newProductThreshold = new Date(); // Current date
    newProductThreshold.setDate(newProductThreshold.getDate() - 30); // 30 days ago

    return new Date(createdAt) >= newProductThreshold;
}
const addToCart = async (product) => {
    try {
        await router.post(route('cart.store', product), {quantity: quantity.value}, {
            onSuccess: page => displayAllNotifications(page)
        })
    } catch (e) {
        console.log(e)
    }

}
const addToWishlist = async (product) => {
    try {
        await router.post(route('wishlist.store', product), null, {
                onSuccess: page => displayAllNotifications(page)
            }
        )
    } catch (e) {
        console.log(e)
    }
}
const selectImage = (imageSrc) => {
    selectedImageSrc.value = imageSrc;
}

const checkIfUserHasReviewedProduct = (reviews) => {
    return reviews.some(review => review.user_id === usePage().props.auth.user.id);
}
</script>
