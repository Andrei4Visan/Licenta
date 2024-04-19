<template>
    <div class="bg-white p-4 rounded-lg shadow animate-fade-in-down">
        <div class="flex justify-between border-b-2 pb-3">
            <div class="flex items-center">
                <span class="whitespace-nowrap mr-3">Per pagina</span>
                <select @change="getProducts(null)" v-model="perPage" class="appearance-none relative block w-24 px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm">
                    <option value="5">5</option>
                    <option value="10">10</option>
                    <option value="20">20</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
<!--                <span class = "ml-3">S-au gasit {{products.total}} products</span>-->

            </div>
        </div>
        <input v-model="search" @change="getProducts(null)"
               class="appearance-none relative block w-48 px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm"
               placeholder="Caută produs">
    </div>
    <table class="table-auto w-full">
        <thead>
        <tr >
            <TableHeaderCell @click="sortProduct" class="border-b-2 p-2 text-left" field = "id" :sort-field = "sortField" :sort-direction = "sortDirections">ID</TableHeaderCell>
            <TableHeaderCell class="border-b-2 p-2 text-left" field = "" :sort-field = "sortField" :sort-direction = "sortDirections">Image</TableHeaderCell>
            <TableHeaderCell @click="sortProduct" class="border-b-2 p-2 text-left" field = "title" :sort-field = "sortField" :sort-direction = "sortDirections">Titlu</TableHeaderCell>
            <TableHeaderCell @click="sortProduct('price')" class="border-b-2 p-2 text-left" field = "price" :sort-field = "sortField" :sort-direction = "sortDirections">Pret</TableHeaderCell>
            <TableHeaderCell @click="sortProduct" class="border-b-2 p-2 text-left" field = "updated_at" :sort-field = "sortField" :sort-direction = "sortDirections">Ultima actualizare</TableHeaderCell>
            <TableHeaderCell field = "actions">
                Actions
            </TableHeaderCell>
        </tr>
        </thead>
        <tbody>
        <tr v-for="(product, index) of fetchedProducts.data" :key="product.id" class = "animate-fade-in-down" :style="{'animation-delay':`${index * 0.1}s`}">
            <td class="border-b p-2">{{ product.id }}</td>
            <td class="border-b p-2">
                <img class="w-16" :src="product.image_url" :alt="product.title">
            </td>
            <td class="border-b p-2 max-w-[200px] whitespace-nowrap overflow-hidden text-ellipsis">{{ product.title }}</td>
            <td class="border-b p-2">{{ product.price }}</td>
            <td class="border-b p-2">{{ product.updated_at }}</td>
            <td class = "border-b p-2">
                <Menu as = "div" class="relative inline-block text-left">
                    <div>
                        <MenuButton
                            class="inline-flex items-center justify-center w-full justify-center rounded-full w-10 h-10 bg-black bg-opacity-0 text-sm font-medium text-white hover:bg-opacity-5 focus:bg-opacity-5 focus:outline-none focus-visible:ring-2 focus-visible:ring-white focus-visible:ring-opacity-75"
                        >
                            <DotsVerticalIcon
                                class = "h-5 w-5 text-orange-500"
                                aria-hidden="true"/>


                        </MenuButton>

                    </div>
                    <transition
                        enter-active-class="transition duration-100 ease-out"
                        enter-from-class="transform sclae-95 opacity-0"
                        enter-to-class="transform scale-100 opacity-100"
                        leave-active-class="transition duration-75 ease-in"
                        leave-from-class="transform scale-100 opacity-100"
                        leave-to-class="transform scale-95 opacity-0">

                        <MenuItems
                            class = "class=absolute z-10 right-0 mt-2 w-32 origin-top-right divide-y divide-gray-100 rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
                        >
                            <div class = "px-1 py-1">
                                <MenuItem v-slot = "{active}">
                                    <button
                                        :class="[
                                            active ? 'bg-orange-600 text-white' : 'text-gray-900',
                                            'group flex w-full items-center rounded-md px-2 py-2 text-sm',
                                        ]"
                                        @click = "editProduct(product)">
                                        <PencilIcon
                                            :active = "active"
                                            class = "mr-2 h-5 w-5 text-orange-400"
                                            aria-hidden="true"/>
                                        Editeaza
                                    </button>
                                </MenuItem>
                                <MenuItem v-slot = "{active}">
                                    <button
                                        :class = "[
                                            active ? 'bg-orange-600 text-white' : 'text-gray-900',
                                            'group flex w-full items-center rounded-md px-2 py-2 text-sm',
                                        ]"
                                        @click = "deleteProduct(product)"
                                    >
                                        <TrashIcon
                                            :active = "active"
                                            class="mr-2 h-5 w-5 text-orange-400"
                                            aria-hidden="true"/>
                                        Sterge
                                    </button>
                                </MenuItem>
                            </div>
                        </MenuItems>
                    </transition>
                </Menu>
            </td>
        </tr>
        </tbody>
    </table>
    <div class="flex justify-between items-center mt-5">
        <span>
            Pagina {{ fetchedProducts.from }} din {{ fetchedProducts.to }}
        </span>
        <nav v-if="fetchedProducts.total > perPage" class="relative z-0 inline-flex justify-center rounded-md shadow-sm -space-x-px" aria-label="Pagination">
            <a v-for="(link, i) of fetchedProducts.links" :key="i"

               href="#"
               @click.prevent="getForPage($event, link)"
               aria-current="page"
               class="relative inline-flex items-center px-4 py-2 border text-sm font-medium whitespace-nowrap"
               :class="[
                    link.active ? 'z-10 bg-orange-50 border-orange-500 text-orange-600'
                    : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50',
                    i === 0 ? 'rounded-l-md' : '',
                    i === fetchedProducts.links.length - 1 ? 'rounded-r-md' : '',
                    !link.url ? 'bg-gray-100 text-gray-700' : ''
               ]"
               v-html="link.label">
            </a>
        </nav>
    </div>
</template>

<script setup>
import { onMounted, ref, computed } from "vue";
import store from "../../store/index.js";
import { PRODUCTS_PER_PAGE } from "../../constants.js";
// import products from "./Products.vue";
import TableHeaderCell from "../../components/core/Table/TableHeaderCell.vue";
import { Menu, MenuButton, MenuItem, MenuItems} from "@headlessui/vue";
import {DotsVerticalIcon, PencilIcon, TrashIcon} from '@heroicons/vue/outline'
import products from "./Products.vue";

const emit = defineEmits(['clickEdit'])
const perPage = ref(PRODUCTS_PER_PAGE);
const search = ref('');
const fetchedProducts = computed(() => store.state.products);
const sortField = ref('updated_at')
const sortDirections = ref('desc')


onMounted(() => {
    getProducts();
});

function getProducts(url = null) {
    store.dispatch('getProducts', {
        url,
        sort_field: sortField.value,
        sort_direction: sortDirections.value,
        search: search.value,
        perPage: perPage.value,
    });
}

function getForPage(ev, link) {
    ev.preventDefault();

    if (!link.url || link.active) {
        return;
    }

    getProducts(link.url); // Trimiteți URL-ul corect pentru a obține pagina corespunzătoare
}

function sortProduct(field) {
    if(sortField.value === field){
        if(sortDirections.value === 'asc'){
            sortDirections.value = 'desc'
        }else{
            sortDirections.value = 'asc'
        }
    }else{
        sortField.value = field;
        sortDirections.value = 'asc'
    }
    getProducts();
}

function editProduct(product){
     emit('clickEdit', product)
}

function deleteProduct(product){
    if(!confirm('Esti sigur ca vrei sa stergi acest produs?')){
        return
    }
    store.dispatch('deleteProduct', product.id)
        .then(res => {
            store.dispatch('getProducts')
        })
}


</script>

<style scoped>
.disabled {
    pointer-events: none;
    opacity: 0.5;
}
</style>
