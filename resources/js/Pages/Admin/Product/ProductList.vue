<template>

    <section class="bg-gray-50 dark:bg-gray-900 p-3 sm:p-5">
        <el-dialog
            v-model="dialogVisible"
            :title="isEditProduct ? 'Edit Product' : 'Add Product'"
            width="30%"
            :before-close="handleDialogClose"
        >
            <form @submit.prevent="isEditProduct? editProduct() : addProduct()" class="max-w-md mx-auto">
                <div class="relative z-0 w-full mb-5 group">
                    <input v-model="title" type="text" name="floating_title" id="floating_title"
                           class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                           placeholder=" " required/>
                    <label for="floating_title"
                           class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Title</label>
                </div>
                <InputError :message="errors['title']" class="mt-2"/>

                <div class="relative z-0 w-full mb-5 group">
                    <input v-model="price" type="number" name="floating_price" id="floating_price" step="0.01"
                           class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                           placeholder=" " required/>
                    <label for="floating_price"
                           class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Price</label>
                </div>
                <InputError :message="errors['price']" class="mt-2"/>

                <div class="relative z-0 w-full mb-5 group">
                    <input v-model="quantity" type="number" name="floating_quantity" id="floating_quantity"
                           class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                           placeholder=" " required/>
                    <label for="floating_quantity"
                           class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Quantity
                    </label>
                </div>
                <InputError :message="errors['quantity']" class="mt-2"/>

                <div>
                    <label for="countries" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Select
                        category</label>
                    <select id="countries" v-model="category_id"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        <option v-for="category in categories" :key="category.id" :value="category.id">{{
                                category.name
                            }}
                        </option>
                    </select>
                </div>
                <div><label for="countries" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Select
                    Brand</label>
                    <select id="countries" v-model="brand_id"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        <option v-for="brand in brands" :key="brand.id" :value="brand.id">{{ brand.name }}</option>

                    </select>
                </div>
                <div>
                    <label for="description" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Description</label>
                    <textarea id="description" rows="4" v-model="description"
                              class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                              placeholder="Add description here..."></textarea>
                </div>
                <InputError :message="errors['description']" class="mt-2"/>

                <div>
                    <label for="message"
                           class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Image</label>
                    <el-upload
                        v-model:file-list="productImages"
                        multiple
                        list-type="picture-card"
                        :on-preview="handlePictureCardPreview"
                        :on-remove="isEditProduct ? handleRemoveFromDb : handleRemove"
                        :on-change="handleFileChange"
                    >
                        <el-icon>
                            <Plus/>
                        </el-icon>
                    </el-upload>
                    <el-dialog v-model="dialogImageVisible">
                        <img w-full :src="dialogImageUrl" alt="Preview Image"/>
                    </el-dialog>
                </div>
                <InputError :message="errors['product_images']" class="mt-2"/>
                <button type="submit"
                        class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                    Submit
                </button>
            </form>

        </el-dialog>
        <div class="mx-auto max-w-screen-xl px-4 lg:px-12">
            <!-- Start coding here -->
            <div class="bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg overflow-visible">
                <div
                    class="flex flex-col md:flex-row items-center justify-between space-y-3 md:space-y-0 md:space-x-4 p-4">
                    <div class="w-full md:w-1/2">
                        <form @submit.prevent="updateFilteredProducts" class="flex items-center">
                            <label for="default-search" class="mb-2 text-sm font-medium text-gray-900 sr-only dark:text-white">Search</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                                    <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                                         xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                                    </svg>
                                </div>
                                <input type="search" v-model="search_input" id="default-search"
                                       class="block w-full lg:w-96 p-4 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                       placeholder="Search Product...">
                                <button type="submit"
                                        class="text-white absolute end-2.5 bottom-2.5 bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                    Search
                                </button>
                            </div>
                        </form>
                    </div>
                    <div
                        class="w-full md:w-auto flex flex-col md:flex-row space-y-2 md:space-y-0 items-stretch md:items-center justify-end md:space-x-3 flex-shrink-0">
                        <button type="button" @click="openAddModal"
                                class="flex items-center justify-center text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                            <svg class="h-3.5 w-3.5 mr-2" fill="currentColor" viewbox="0 0 20 20"
                                 xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                <path clip-rule="evenodd" fill-rule="evenodd"
                                      d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"/>
                            </svg>
                            Add product
                        </button>
                        <div class="flex items-center space-x-3 w-full md:w-auto">
                            <button id="actionsDropdownButton" data-dropdown-toggle="actionsDropdown"
                                    class="w-full md:w-auto flex items-center justify-center py-2 px-4 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-primary-700 focus:z-10 focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700"
                                    type="button">
                                <svg class="-ml-1 mr-1.5 w-5 h-5" fill="currentColor" viewbox="0 0 20 20"
                                     xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                    <path clip-rule="evenodd" fill-rule="evenodd"
                                          d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"/>
                                </svg>
                                Actions
                            </button>
                            <div id="actionsDropdown"
                                 class="hidden z-10 w-44 bg-white rounded divide-y divide-gray-100 shadow dark:bg-gray-700 dark:divide-gray-600">
                                <ul class="py-1 text-sm text-gray-700 dark:text-gray-200"
                                    aria-labelledby="actionsDropdownButton">
                                    <li>
                                        <a href="#"
                                           class="block py-2 px-4 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Mass
                                            Edit</a>
                                    </li>
                                </ul>
                                <div class="py-1">
                                    <a href="#"
                                       class="block py-2 px-4 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">Delete
                                        all</a>
                                </div>
                            </div>
                            <button id="filterDropdownButton" data-dropdown-toggle="filterDropdown"
                                    class="w-full md:w-auto flex items-center justify-center py-2 px-4 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-primary-700 focus:z-10 focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700"
                                    type="button">
                                <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true"
                                     class="h-4 w-4 mr-2 text-gray-400" viewbox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd"
                                          d="M3 3a1 1 0 011-1h12a1 1 0 011 1v3a1 1 0 01-.293.707L12 11.414V15a1 1 0 01-.293.707l-2 2A1 1 0 018 17v-5.586L3.293 6.707A1 1 0 013 6V3z"
                                          clip-rule="evenodd"/>
                                </svg>
                                Filter
                                <svg class="-mr-1 ml-1.5 w-5 h-5" fill="currentColor" viewbox="0 0 20 20"
                                     xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                    <path clip-rule="evenodd" fill-rule="evenodd"
                                          d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"/>
                                </svg>
                            </button>
                            <div id="filterDropdown"
                                 class="z-10 hidden w-48 p-3 bg-white rounded-lg shadow dark:bg-gray-700 max-h-72 overflow-y-auto   ">
                                <h6 class="mb-3 text-sm font-medium text-gray-900 dark:text-white">Choose brand</h6>
                                <ul class="space-y-2 text-sm" aria-labelledby="filterDropdownButton">
                                    <li class="flex items-center" v-for="brand in brands" :key="brand.id">
                                        <input :id="`filter-brand-${brand.id}`" type="checkbox" v-model="selectedBrands"  :value="brand.id"
                                               class="w-4 h-4 bg-gray-100 border-gray-300 rounded text-primary-600 focus:ring-primary-500 dark:focus:ring-primary-600 dark:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">
                                        <label :for="`filter-brand-${brand.id}`"
                                               class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-100">{{ brand.name }}
                                            ({{ getBrandProductCount(brand) }})</label>
                                    </li>
                                </ul>
                                <h6 class="mb-3 text-sm font-medium text-gray-900 dark:text-white">Choose category</h6>
                                <ul class="space-y-2 text-sm" aria-labelledby="filterDropdownButton">
                                    <li class="flex items-center" v-for="category in categories" :key="category.id" >
                                        <input :id="`filter-category-${category.id}`" type="checkbox" v-model="selectedCategories" :value="category.id"
                                               class="w-4 h-4 bg-gray-100 border-gray-300 rounded text-primary-600 focus:ring-primary-500 dark:focus:ring-primary-600 dark:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">
                                        <label :for="`filter-category-${category.id}`"
                                               class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-100">{{ category.name }}
                                            ({{ getCategoryProductCount(category) }})</label>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-4 py-3">Product name</th>
                            <th scope="col" class="px-4 py-3">Category</th>
                            <th scope="col" class="px-4 py-3">Brand</th>
                            <th scope="col" class="px-4 py-3">Quantity</th>
                            <th scope="col" class="px-4 py-3">Price</th>
                            <th scope="col" class="px-4 py-3">In stock</th>
                            <th scope="col" class="px-4 py-3">Published</th>
                            <th scope="col" class="px-4 py-3">
                                <span class="sr-only">Actions</span>
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="product in products.data" :key="product.id" class="border-b dark:border-gray-700">
                            <th scope="row"
                                class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{ product.title }}
                            </th>
                            <td class="px-4 py-3">{{ product.category.name }}</td>
                            <td class="px-4 py-3">{{ product.brand.name }}</td>
                            <td class="px-4 py-3">{{ product.quantity }}</td>
                            <td class="px-4 py-3">${{ product.price }}</td>
                            <td class="px-4 py-3">
                                <span v-if="product.quantity > 0"
                                    class="bg-green-100 text-green-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-green-900 dark:text-green-300">Available</span>
                                <span v-else
                                    class="bg-red-100 text-red-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-red-900 dark:text-red-300">Not Available</span>

                            </td>
                            <td class="px-4 py-3">
                                <button @click="publish(product)" v-if="product.published" type="button"
                                        class="px-3 py-2 text-xs font-medium text-center text-white bg-red-700 rounded-lg hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800">
                                    Unpublish
                                </button>
                                <button @click="publish(product)" v-else type="button"

                                    class="px-3 py-2 text-xs font-medium text-center text-white bg-green-700 rounded-lg hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
                                    Publish
                                </button>
                            </td>
                            <td class="px-4 py-3 flex items-center justify-end">
                                <button :id="`${product.id}-dropdown-button`" :data-dropdown-toggle="`${product.id}-dropdown`"
                                        class="inline-flex items-center p-0.5 text-sm font-medium text-center text-gray-500 hover:text-gray-800 rounded-lg focus:outline-none dark:text-gray-400 dark:hover:text-gray-100"
                                        type="button">
                                    <svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewbox="0 0 20 20"
                                         xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 0 000 4z"/>
                                    </svg>
                                </button>
                                <div :id="`${product.id}-dropdown`"
                                     class="hidden z-10 w-44 bg-white rounded divide-y divide-gray-100 shadow dark:bg-gray-700 dark:divide-gray-600">
                                    <ul class="py-1 text-sm text-gray-700 dark:text-gray-200"
                                        :aria-labelledby="`${product.id}-dropdown-button`">
                                        <li>
                                            <Link :href="route('products.showDetails', product.id)"
                                               class="block py-2 px-4 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Show</Link>
                                        </li>
                                        <li>
                                            <button @click="openEditModal(product)"
                                                    class="block py-2 px-4 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">
                                                Edit
                                            </button>
                                        </li>
                                    </ul>
                                    <div class="py-1">
                                        <button @click="deleteProduct(product)"
                                           class="block py-2 px-4 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">Delete</button>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <nav
                    class="flex flex-col md:flex-row justify-between items-start md:items-center space-y-3 md:space-y-0 p-4"
                    aria-label="Table navigation">
                    <Pagination :links="products.links"/>


                </nav>
            </div>
        </div>
    </section>
</template>

<script setup>
import {usePage,Link} from "@inertiajs/vue3";
import {computed, ref, watch} from "vue";
import {router} from "@inertiajs/vue3";
import Swal from "sweetalert2";
import {Plus} from '@element-plus/icons-vue'
import Pagination from "@/Components/Pagination.vue";
import InputError from "@/Components/InputError.vue";


defineProps({
    products: {
        type: Array,
        default: () => []
    }
})


const brands = usePage().props.brands
const categories = usePage().props.categories

const errors = computed(() => {
    return usePage().props.errors
})

const selectedBrands = ref([])
const selectedCategories = ref([])



watch(selectedBrands, () => {
    updateFilteredProducts()
})

watch(selectedCategories, () => {
    updateFilteredProducts()
})
const getBrandProductCount = (brand) => {
    return usePage().props.brandProductCounts[brand.id]
}
const getCategoryProductCount = (category) => {
    return usePage().props.categoryProductCounts[category.id]
}

const search_input = ref('');



const updateFilteredProducts = async () => {
    const data = {
        brands: selectedBrands.value,
        categories: selectedCategories.value,
        search: search_input.value,
        //sort: sortOptions.find((option) => option.current).slug
    }

    try {
        await router.get(route('admin.products.index'), data, {
            preserveState: true,
            replace: true,
        })
    } catch (e) {
        console.log(e)
    }

}

const id = ref('');
const title = ref('');
const description = ref('');
const price = ref('');
const quantity = ref('');
const category_id = ref('');
const brand_id = ref('');
const productImages = ref([]);
const product_images = ref([]);

const dialogImageUrl = ref('');
const dialogImageVisible = ref(false);
const handleFileChange = (file) => {
    productImages.value.push(file)
}
const handlePictureCardPreview = (file) => {
    dialogImageUrl.value = file.url
    dialogImageVisible.value = true
}
const handleRemove = (file) => {
}
const handleRemoveFromDb = async (file) => {
    if(!file.id) return;
    try {
        await router.delete(`/admin/products/image/delete/${file.id}`, {
            onSuccess: (page) => {
                Swal.fire({
                    toast: true,
                    icon: "success",
                    position: "top-end",
                    showConfirmButton: false,
                    title: page.props.flash.success
                })
                product_images.value = product_images.value.filter(image => image.id !== file.id)
            },
        })
    } catch (e) {
        console.log(e)
    }
}
const addProduct = async () => {
    const formData = new FormData();
    formData.append('title', title.value);
    formData.append('description', description.value);
    formData.append('price', price.value);
    formData.append('quantity', quantity.value);
    formData.append('category_id', category_id.value);
    formData.append('brand_id', brand_id.value);

    for (const image of productImages.value) {
        formData.append('product_images[]', image.raw);
    }

    try {
        await router.post('/admin/products/store', formData, {
            onSuccess: page => {
                Swal.fire({
                    toast: true,
                    icon: "success",
                    position: "top-end",
                    showConfirmButton: false,
                    timer: 1500,
                    title: page.props.flash.success
                })
                dialogVisible.value = false;
                resetForm();
            },
        })
    } catch (e) {
        console.log(e)
    }
}
const editProduct = async () => {
    const formData = new FormData();
    formData.append('title', title.value);
    formData.append('description', description.value);
    formData.append('price', price.value);
    formData.append('quantity', quantity.value);
    formData.append('category_id', category_id.value);
    formData.append('brand_id', brand_id.value);
    formData.append('_method', 'PUT');

    for (const image of productImages.value) {
        formData.append('product_images[]', image.raw);
    }

    try {
        await router.post(`/admin/products/update/${id.value}`, formData, {
            onSuccess: page => {
                Swal.fire({
                    toast: true,
                    icon: "success",
                    position: "top-end",
                    showConfirmButton: false,
                    timer: 1500,
                    title: page.props.flash.success
                })
                dialogVisible.value = false;
                resetForm();
            },
        })

    } catch (e) {
        console.log(e)
    }
}
const deleteProduct = async(product) => {
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',

        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete!',
        cancelButtonText: 'Cancel'
    }).then(async (result) => {
        if (result.isConfirmed) {
            try {
                await router.delete(`/admin/products/delete/${product.id}`, {
                    onSuccess: page => {
                        Swal.fire({
                            toast: true,
                            icon: "success",
                            position: "top-end",
                            showConfirmButton: false,
                            timer: 1500,
                            title: page.props.flash.success
                        })
                    },
                })
            } catch (e) {
                console.log(e)
            }
        }
    })

}




const isAddProduct = ref(false);
const isEditProduct = ref(false);
const dialogVisible = ref(false);
const openAddModal = () => {
    isAddProduct.value = true;
    dialogVisible.value = true;
    isEditProduct.value = false;
}
const openEditModal = (product) => {
    isEditProduct.value = true;
    isAddProduct.value = false;
    dialogVisible.value = true;
    id.value = product.id;
    title.value = product.title;
    description.value = product.description;
    price.value = product.price;
    quantity.value = product.quantity;
    category_id.value = product.category_id;
    brand_id.value = product.brand_id;
    for(const image of product.product_images){
        image.url = `/storage/${image.image}`
        productImages.value.push(image)
    }
}

const handleDialogClose = () => {
    dialogVisible.value = false;
    resetForm();
}
const resetForm = () => {
    title.value = '';
    description.value = '';
    price.value = '';
    quantity.value = '';
    category_id.value = '';
    brand_id.value = '';
    productImages.value = [];
    dialogImageUrl.value = '';
}

const publish = async (product) => {
    try{
        await router.post(`/admin/products/publish/${product.id}`,{'_method': 'PATCH'},{
            onSuccess: page => {
                Swal.fire({
                    toast: true,
                    icon: "success",
                    position: "top-end",
                    showConfirmButton: false,
                    timer: 1500,
                    title: page.props.flash.success
                })
            },
        })
    } catch(e) {
        console.log(e)
    }
}

</script>
