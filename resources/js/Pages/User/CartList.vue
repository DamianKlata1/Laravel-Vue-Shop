<template>
    <UserLayout>
        <section class="text-gray-600 body-font relative">
            <div class="container px-5 py-24 mx-auto flex sm:flex-nowrap flex-wrap">
                <div
                    class="lg:w-2/3 md:w-1/2 rounded-lg sm:mr-10 p-10 ">

                    <!--                     list of cards-->


                    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
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
                                    Qty
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
                            <tr v-for="cartItem in cartItems.data" :key="cartItem.id"
                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <td class="p-4">
                                    <img v-if="cartItem.product.product_images.length >0"
                                         :src="`/storage/${cartItem.product.product_images[0].image}`"
                                         class="w-16 md:w-32 max-w-full max-h-full" alt="product image">
                                    <img v-else src="/icons/image-not-found-icon.png"
                                         class="w-16 md:w-32 max-w-full max-h-full" alt="product image">
                                </td>
                                <td class="px-6 py-4 font-semibold text-gray-900 dark:text-white">
                                    {{ cartItem.product.title }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <button
                                            @click.prevent="update(cartItem.quantity - 1,cartItem.product)"
                                            :disabled="cartItem.quantity <= 1"
                                            :class="[cartItem.quantity >1 ? 'cursor-pointer text-purple-600':
                                                 'cursor-not-allowed text-gray-300 dark:text-gray-500',  'inline-flex items-center justify-center p-1 me-3 text-sm font-medium h-6 w-6 text-gray-500 bg-white border border-gray-300 rounded-full focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-200 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700']"
                                            type="button"
                                        >
                                            <span class="sr-only">Quantity button</span>
                                            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                                 fill="none" viewBox="0 0 18 2">
                                                <path stroke="currentColor" stroke-linecap="round"
                                                      stroke-linejoin="round" stroke-width="2" d="M1 1h16"/>
                                            </svg>
                                        </button>
                                        <div>
                                            <input
                                                v-model="cartItem.quantity"
                                                type="number" id="first_product"
                                                class="bg-gray-50 w-14 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block px-2.5 py-1 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                                placeholder="1" required>
                                        </div>
                                        <button
                                            @click.prevent="update(cartItem.quantity + 1,cartItem.product)"
                                            class="inline-flex items-center justify-center h-6 w-6 p-1 ms-3 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-full focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-200 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700"
                                            type="button">
                                            <span class="sr-only">Quantity button</span>
                                            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                                 fill="none" viewBox="0 0 18 18">
                                                <path stroke="currentColor" stroke-linecap="round"
                                                      stroke-linejoin="round" stroke-width="2" d="M9 1v16M1 9h16"/>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                                <td class="px-6 py-4 font-semibold text-gray-900 dark:text-white">
                                    ${{ cartItem.product.price }}
                                </td>
                                <td class="px-6 py-4">
                                    <button @click="remove(cartItem.product)"
                                            class="font-medium text-red-600 dark:text-red-500 hover:underline">Remove
                                    </button>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>

                </div>
                <div class="lg:w-1/3 md:w-1/2 bg-white flex flex-col md:ml-auto w-full md:py-8 mt-8 md:mt-0">
                    <h2 class="text-gray-900 text-lg mb-1 font-medium title-font">Summary</h2>
                    <p class="leading-relaxed mb-5 text-gray-600">Total : ${{ total.toFixed(2) }}</p>
                    <div v-if="userAddress">
                        <h2 class="text-gray-900 text-lg mb-1 font-medium title-font">Shipping address</h2>
                        <p class="leading-relaxed mb-5 text-gray-600">{{ userAddress.address1 }}, {{
                                userAddress.city
                            }},
                            {{ userAddress.state }}, {{ userAddress.zipcode }} </p>
                        <p class="leading-relaxed mb-5 text-gray-600">or you can add new <a href="#"
                                                                                            @click.prevent="addressFormVisibleToggle"
                                                                                            style="font-size: 1.2em; color: #ff6600;">here</a>
                        </p>
                        <button v-if="!addressFormVisible" @click.prevent="submitCheckout"
                                class="text-white bg-indigo-500 border-0 py-2 px-6 focus:outline-none hover:bg-indigo-600 rounded text-lg">
                            Checkout
                        </button>
                    </div>
                    <div v-else>
                        <p class="leading-relaxed mb-5 text-gray-600">Add shipping address to continue</p>
                    </div>
                    <form @submit.prevent="submitAddressForm" v-show="addressFormVisible">
                        <div class="relative mb-4">
                            <label for="type" class="leading-7 text-sm text-gray-600">Type</label>
                            <TextInput
                                id="type"
                                v-model="addressForm.type"
                                type="text"
                                class="w-full bg-white rounded border border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out"

                            />
                            <InputError :message="errors['type']" class="mt-2"/>

                        </div>
                        <div class="relative mb-4">
                            <label for="address" class="leading-7 text-sm text-gray-600">Address</label>
                            <input type="text" id="address" v-model="addressForm.address"
                                   class="w-full bg-white rounded border border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                            <InputError :message="errors['address']" class="mt-2"/>

                        </div>
                        <div class="relative mb-4">
                            <label for="city" class="leading-7 text-sm text-gray-600">City</label>
                            <input type="text" id="city" v-model="addressForm.city"
                                   class="w-full bg-white rounded border border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                        </div>
                        <InputError :message="errors['city']" class="mt-2"/>

                        <div class="relative mb-4">
                            <label for="state" class="leading-7 text-sm text-gray-600">State</label>
                            <input type="text" id="state" v-model="addressForm.state"
                                   class="w-full bg-white rounded border border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                            <InputError :message="errors['state']" class="mt-2"/>

                        </div>
                        <div class="relative mb-4">
                            <label for="zipcode" class="leading-7 text-sm text-gray-600">Zipcode</label>
                            <input type="text" id="zipcode" v-model="addressForm.zipcode"
                                   class="w-full bg-white rounded border border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                            <InputError :message="errors['zipcode']" class="mt-2"/>

                        </div>
                        <div class="relative mb-4">
                            <label for="country_code" class="leading-7 text-sm text-gray-600">Country Code</label>
                            <input type="text" id="country_code" v-model="addressForm.country_code"
                                   class="w-full bg-white rounded border border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                            <InputError :message="errors['country_code']" class="mt-2"/>

                        </div>
                        <button type="submit"
                                class="text-white bg-indigo-500 border-0 py-2 px-6 focus:outline-none hover:bg-indigo-600 rounded text-lg">
                            Add address
                        </button>

                    </form>
                    <Link href="/" class="text-xs text-gray-500 mt-3">Continue shopping</Link>
                </div>
            </div>
        </section>
    </UserLayout>

</template>
<script setup>
import UserLayout from "./Layouts/UserLayout.vue";
import {computed, reactive, ref} from "vue";
import {router, useForm, usePage, Link} from "@inertiajs/vue3";
import {displayAllNotifications} from "@/Helpers/notification.js";
import InputError from "@/Components/InputError.vue";
import TextInput from "@/Components/TextInput.vue";

defineProps({
    userAddress: Object,
    cartItems: Object,
    errors: Object
})

const addressFormVisible = ref(usePage().props.userAddress === null)

const addressFormVisibleToggle = () => {
    addressFormVisible.value = !addressFormVisible.value
}

const total = computed(() => {
    return usePage().props.total
})
const cartItems = computed(() => {
    return usePage().props.cartItems
})
const errors = computed(() => {
    return usePage().props.errors
})
const getCartItemIndexByProductId = (id) => {
    return cartItems.data.findIndex(item => item.product_id === id)
}
const update = async (quantity, product) => {
    try {
        await router.patch(route('cart.update', product), {
            quantity
        })
    } catch (e) {
        console.log(e)
    }
}
const remove = async (product) => {
    try {
        await router.delete(route('cart.delete', product))
    } catch (e) {
        console.log(e)
    }
}
const addressForm = useForm({
    type: '',
    address: '',
    city: '',
    state: '',
    zipcode: '',
    country_code: '',
})

const formFilled = computed(() => {
    return addressForm.type !== '' && addressForm.address !== '' && addressForm.city !== '' && addressForm.state !== ''
        && addressForm.zipcode !== '' && addressForm.country_code !== ''
})
const submitCheckout = async () => {
    try {
        await router.post(route('checkout.store'), {
            cartItems: usePage().props.cartItems.data,
            total: usePage().props.total
        }, {
            onSuccess: (page) => {
                displayAllNotifications(page)
            },
        })
    } catch (e) {
        console.log(e)
    }
}
const submitAddressForm = async () => {
    const formData = new FormData();
    formData.append('type', addressForm.type)
    formData.append('address', addressForm.address)
    formData.append('city', addressForm.city)
    formData.append('state', addressForm.state)
    formData.append('zipcode', addressForm.zipcode)
    formData.append('country_code', addressForm.country_code)
    formData.append('_method', 'PATCH')

    try {
        await router.post(route('profile.updateAddress'), formData, {
            preserveScroll: true,
            onSuccess: (page) => {
                displayAllNotifications(page)
                addressForm.reset()
                addressFormVisibleToggle()
            }
        })
    } catch (e) {
        console.log(e)
    }
}
</script>
