<template>

    <div class="flex items-center justify-between mb-3">
        <h1 class="text-3xl font-semibold">Produse</h1>
        <button type="submit"
                @click="showProductModal"
                class="flex justify-center py-2 pc-4 border border-transparent text-sm font-medium rounded-md text-white bg-orange-800 hover:bg-white">
            Adauga un nou produs:
        </button>
    </div>

    <ProductModal v-model="showModal" :product="productModel" @close = "onModalClose"/>
    <ProductsTable @clickEdit="editProduct"/>

</template>

<script setup>

import {ref} from "vue";
import ProductsTable from "./ProductsTable.vue";
import ProductModal from "./ProductModal.vue";
import store from "../../store/index.js";
const DEFAULT_EMPTY_OBJECT = {
    id: '',
    title: '',
    image: '',
    description: '',
    price: '',
}
const showModal = ref(false)
const productModel = ref({...DEFAULT_EMPTY_OBJECT})

function showProductModal(){
    showModal.value = true
}

function editProduct(product){
    store.dispatch('getProduct', product.id)
        .then(({data})=>{
            productModel.value = data
            showProductModal()

        })

}

function onModalClose(){
    productModel.value = {...DEFAULT_EMPTY_OBJECT}
}
</script>

<style scoped>
.disabled {
    pointer-events: none;
    opacity: 0.5;
}
</style>
