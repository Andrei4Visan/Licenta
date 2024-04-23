<template>
    <div v-if ="order">
    <h2 class="flex justify-between items-center text-2xl font-bold mt-6 pb-2 border-b border-gray-400" >
        Detaliile comenzii
        <OrderStatus :order = "order"/>
    </h2>
    <table>
        <tbody>
        <tr>
            <td class="font-bold py-1 px-2">Comandă</td>
            <td>{{order.id}}</td>
        </tr>
        <tr>
            <td class="font-bold py-1 px-2">Data comenzii</td>
            <td>{{order.created_at}}</td>
        </tr>
        <tr>
            <td class="font-bold py-1 px-2">Statusul comeznii</td>
            <td>
                <select v-model="order.status" @change="onStatusChange">
                    <option v-for="status in orderStatuses" :value="status">{{ status }}</option>
                </select>

            </td>
        </tr>
        <tr>
            <td class="font-bold py-1 px-2">SubTotal</td>
            <td>{{ order.total_price }} Lei</td>
        </tr>
        </tbody>
    </table>
        <div>
            <h2 class="text-xl font-semibold mt-6 pb-2 border-b border-gray-300">Customer Details</h2>
            <table>
                <tbody>
                <tr>
                    <td class="font-bold py-1 px-2">Full Name</td>
                    <td>{{ order.customer.first_name }} {{ order.customer.last_name }}</td>
                </tr>
                <tr>
                    <td class="font-bold py-1 px-2">Email</td>
                    <td>{{ order.customer.email }}</td>
                </tr>
                <tr>
                    <td class="font-bold py-1 px-2">Phone</td>
                    <td>{{ order.customer.phone }}</td>
                </tr>
                </tbody>
            </table>
        </div>


    <div class = "flex-1">

        <h2 class = "text-2xl font-bold mt-6 pb-2 border-b border-gray-300">Billing Address</h2>

        <div>
            {{ order.customer.billingAddress.address1 }}, {{ order.customer.billingAddress.address2 }} <br>
            {{ order.customer.billingAddress.city }}, {{ order.customer.billingAddress.zipcode }} <br>
            {{ order.customer.billingAddress.state }}, {{ order.customer.billingAddress.country }} <br>
        </div>
    </div>
    <div class = "flex-1">

        <h2 class = "text-2xl font-bold mt-6 pb-2 border-b border-gray-300">Shipping Address</h2>

        <div>
            {{ order.customer.shippingAddress.address1 }}, {{ order.customer.shippingAddress.address2 }} <br>
            {{ order.customer.shippingAddress.city }}, {{ order.customer.shippingAddress.zipcode }} <br>
            {{ order.customer.shippingAddress.state }}, {{ order.customer.shippingAddress.country }} <br>
        </div>


    </div>



    <h2 class="text-2xl font-bold mt-6 pb-2 border-b border-gray-400" > Produse comandate</h2>

    <template v-for = "item of order.items">
        <!-- Order Item -->
        <div class="flex flex-col sm:flex-row items-center gap-4">
            <a href="#" class="w-36 h-32 flex items-center justify-center overflow-hidden">
                <img :src="item.product.image" class="object-cover" alt=""/>
            </a>
            <div class="flex flex-col justify-between w-full">
                <div class="flex justify-between mb-3">
                    <h3>{{item.product.title}}</h3>
                </div>
                <div class="flex justify-between items-center w-full">
                    <div>Cantitate: {{item.quantity}}</div>
                    <span class="text-lg font-semibold">{{item.unit_price}} Lei</span>
                </div>
            </div>
        </div>

        <!--/ Order Item -->
        <hr class="my-3"/>

    </template>

    </div>
</template>

<script setup>

import {computed, onMounted, ref} from "vue";
import OrdersTable from "./OrdersTable.vue";

import store from "../../store/index.js";
import {useRoute, useRouter} from "vue-router";
import axiosClient from "../../axios.js";
import OrderStatus from "./OrderStatus.vue";

const route = useRoute()

const order = ref(null);
const orderStatuses = ref([]);


onMounted(()=>{
    store.dispatch('getOrder', route.params.id)
        .then(({data})=>{
            order.value = data
        })
    axiosClient.get('/orders/statuses')
        .then(({data}) => {
            orderStatuses.value = data
        })

})

function onStatusChange(){
    axiosClient.post(`/orders/change-status/${order.value.id}/${order.value.status}`)
        .then(({data}) => {
            console.log("Succes!")
        })
}
function showOrder(product){


}



</script>

<style scoped>
.disabled {
  pointer-events: none;
  opacity: 0.5;
}
</style>
