<template>
    <div class="bg-white p-4 rounded-lg shadow animate-fade-in-down">
        <div class="flex justify-between border-b-2 pb-3">
            <div class="flex items-center">
                <span class="whitespace-nowrap mr-3">Per pagina</span>
                <select @change="getOrders(null)" v-model="perPage" class="appearance-none relative block w-24 px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm">
                    <option value="5">5</option>
                    <option value="10">10</option>
                    <option value="20">20</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
                <!--                <span class = "ml-3">S-au gasit {{orders.total}} orders</span>-->

            </div>
        </div>
        <input v-model="search" @change="getOrders(null)"
               class="appearance-none relative block w-48 px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm"
               placeholder="Caută comandă">
    </div>
    <table class="table-auto w-full">
        <thead>
        <tr >
            <TableHeaderCell @click="sortOrder" class="border-b-2 p-2 text-left" field = "id" :sort-field = "sortField" :sort-direction = "sortDirections">ID</TableHeaderCell>
            <TableHeaderCell @click="sortOrder('customer')" class="border-b-2 p-2 text-left"  :sort-field = "sortField" :sort-direction = "sortDirections">Client</TableHeaderCell>
            <TableHeaderCell @click="sortOrder('status')" class="border-b-2 p-2 text-left" field = "status" :sort-field = "sortField" :sort-direction = "sortDirections">Status</TableHeaderCell>
            <TableHeaderCell @click="sortOrder('created_at')" class="border-b-2 p-2 text-left" field = "created_at" :sort-field = "sortField" :sort-direction = "sortDirections">Data</TableHeaderCell>
            <TableHeaderCell @click="sortOrder('total_price')" class="border-b-2 p-2 text-left" field = "total_price" :sort-field = "sortField" :sort-direction = "sortDirections">Pret</TableHeaderCell>
            <TableHeaderCell field = "actions">
                Actions
            </TableHeaderCell>
        </tr>
        </thead>
        <tbody>
        <tr v-for="(order, index) of fetchedOrders.data" :key="order.id" class = "animate-fade-in-down" :style="{'animation-delay':`${index * 0.1}s`}">
            <td class="border-b p-2">{{ order.id }}</td>
            <td class="border-b p-2">{{ order.customer.first_name }} {{ order.customer.last_name }}</td>
            <td class="border-b p-2">
                <OrderStatus :order = "order"/>
            </td>
            <td class="border-b p-2 max-w-[200px] whitespace-nowrap overflow-hidden text-ellipsis">{{ order.created_at }}</td>
            <td class="border-b p-2">{{ order.total_price }} Lei</td>
            <td class = "border-b p-2">
                <router-link :to = "{name:'app.orders.view', params: {id: order.id}}"
                             class = "w-8 h-8 text-orange-700 rounded-full border border-orange-700 flex justify-center items-center hover:text-white hover:bg-orange-700">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                    </svg>

                </router-link>
            </td>
        </tr>
        </tbody>
    </table>
    <div class="flex justify-between items-center mt-5">
        <span>
            Pagina {{ fetchedOrders.from }} din {{ fetchedOrders.to }}
        </span>
        <nav v-if="fetchedOrders.total > perPage" class="relative z-0 inline-flex justify-center rounded-md shadow-sm -space-x-px" aria-label="Pagination">
            <a v-for="(link, i) of fetchedOrders.links" :key="i"

               href="#"
               @click.prevent="getForPage($event, link)"
               aria-current="page"
               class="relative inline-flex items-center px-4 py-2 border text-sm font-medium whitespace-nowrap"
               :class="[
                    link.active ? 'z-10 bg-orange-50 border-orange-500 text-orange-600'
                    : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50',
                    i === 0 ? 'rounded-l-md' : '',
                    i === fetchedOrders.links.length - 1 ? 'rounded-r-md' : '',
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
// import orders from "./Orders.vue";
import TableHeaderCell from "../../components/core/Table/TableHeaderCell.vue";
import { Menu, MenuButton, MenuItem, MenuItems} from "@headlessui/vue";
import {DotsVerticalIcon, PencilIcon, TrashIcon} from '@heroicons/vue/outline'
import orders from "./Orders.vue";
import OrderStatus from "./OrderStatus.vue";

const emit = defineEmits(['clickEdit'])
const perPage = ref(PRODUCTS_PER_PAGE);
const search = ref('');
const fetchedOrders = computed(() => store.state.orders);
const sortField = ref('updated_at')
const sortDirections = ref('desc')


onMounted(() => {
    getOrders();
});

function getOrders(url = null) {
    store.dispatch('getOrders', {
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

    getOrders(link.url); // Trimiteți URL-ul corect pentru a obține pagina corespunzătoare
}

function sortOrder(field) {
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
    getOrders();
}

function showOrder(order){
    emit('clickShow', order)
}

function deleteOrder(order){
    if(!confirm('Esti sigur ca vrei sa stergi acest produs?')){
        return
    }
    store.dispatch('deleteOrder', order.id)
        .then(res => {
            store.dispatch('getOrders')
        })
}


</script>

<style scoped>
.disabled {
    pointer-events: none;
    opacity: 0.5;
}
</style>
