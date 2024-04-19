<template>
    {{order}}
</template>

<script setup>

import {computed, onMounted, ref} from "vue";
import OrdersTable from "./OrdersTable.vue";

import store from "../../store/index.js";
import {useRoute, useRouter} from "vue-router";

const route = useRoute()


onMounted(()=>{
    store.dispatch('getOrder', route.params.id)
})
function showOrder(product){


}



</script>

<style scoped>
.disabled {
  pointer-events: none;
  opacity: 0.5;
}
</style>
