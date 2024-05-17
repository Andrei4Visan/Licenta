<template>
    <h1 class="text-4xl mb-6 text-center text-gray-800 font-bold">Dashboard</h1>
    <div class="flex justify-center">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <div class="bg-blue-500 text-white py-8 px-6 rounded-lg shadow-lg flex flex-col items-center justify-center hover:shadow-xl transition-shadow duration-300">
                <label class="text-xl font-medium">Clienti activi</label>
                <span class="text-9xl font-semibold mt-2">{{ customersCount }}</span>
            </div>
            <div class="bg-green-500 text-white py-8 px-6 rounded-lg shadow-lg flex flex-col items-center justify-center hover:shadow-xl transition-shadow duration-300">
                <label class="text-xl font-medium">Produse active</label>
                <span class="text-9xl font-semibold mt-2">{{ productsCount }}</span>
            </div>
            <div class="bg-yellow-500 text-white py-8 px-6 rounded-lg shadow-lg flex flex-col items-center justify-center hover:shadow-xl transition-shadow duration-300">
                <label class="text-xl font-medium">Comenzi platite</label>
                <span class="text-9xl font-semibold mt-2">{{ paidOrders }}</span>
            </div>
        </div>
    </div>
    <div class="flex justify-center">
        <div class="bg-red-500 text-white py-8 px-6 rounded-lg shadow-lg flex flex-col items-center justify-center hover:shadow-xl transition-shadow duration-300 w-full md:max-w-md">
            <label class="text-xl font-medium">Suma incasata</label>
            <span class="text-6xl font-semibold mt-2">{{ totalIncome }} Lei</span>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import axiosClient from '../axios.js';

const customersCount = ref(0);
const productsCount = ref(0);
const paidOrders = ref(0);
const totalIncome = ref(0);

axiosClient.get(`/dashboard/customers-count`).then(({ data }) => customersCount.value = data);
axiosClient.get(`/dashboard/products-count`).then(({ data }) => productsCount.value = data);
axiosClient.get(`/dashboard/orders-count`).then(({ data }) => paidOrders.value = data);
axiosClient.get(`/dashboard/income-amount`).then(({ data }) => totalIncome.value = data);
</script>

<style scoped>
@tailwind base;
@tailwind components;
@tailwind utilities;
</style>
