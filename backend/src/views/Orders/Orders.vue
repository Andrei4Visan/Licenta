<template>

  <div class="flex items-center justify-between mb-3">
    <h1 class="text-3xl font-semibold">Comenzi</h1>
  </div>


  <OrdersTable @clickView="viewOrder"/>

</template>

<script setup>

import {computed, ref} from "vue";
import OrdersTable from "./OrdersTable.vue";

import store from "../../store/index.js";

const orders = computed(() => store.state.orders);
function showOrder(product){


}



</script>

<style scoped>
.disabled {
  pointer-events: none;
  opacity: 0.5;
}
</style>
