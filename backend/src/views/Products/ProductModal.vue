<template>

    <TransitionRoot appear :show="show" as="template">
        <Dialog as="div" @close="closeModal" class="relative z-10">
            <TransitionChild
                as="template"
                enter="duration-300 ease-out"
                enter-from="opacity-0"
                enter-to="opacity-100"
                leave="duration-200 ease-in"
                leave-from="opacity-100"
                leave-to="opacity-0"
            >
                <div class="fixed inset-0 bg-black bg-opacity-80" />
            </TransitionChild>

            <div class="fixed inset-0 overflow-y-auto">
                <div
                    class="flex min-h-full items-center justify-center p-4 text-center"
                >
                    <TransitionChild
                        as="template"
                        enter="duration-300 ease-out"
                        enter-from="opacity-0 scale-95"
                        enter-to="opacity-100 scale-100"
                        leave="duration-200 ease-in"
                        leave-from="opacity-100 scale-100"
                        leave-to="opacity-0 scale-95"
                    >
                        <DialogPanel
                            class="w-full max-w-md transform overflow-hidden rounded-2xl bg-white p-6 text-left align-middle shadow-xl transition-all"
                        >
                            <header class = "py-3 px-4 flex justify-between items-center">
                                <DialogTitle as = "h3" class = "text-lg leading-6 font-medium text-gray-900">
                                    {{ product.id ? `Actualizează produsul: "${props.product.title}" ` : 'Creează un produs nou' }}
                                </DialogTitle>
                                <button
                                    @click = "closeModal()"
                                    class="w-8 h-8 flex items-center justify-center rounded-full transition-colors cursor-pointer hover:bg-gray-200 hover:text-gray-600"
                                    >
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                    </svg>

                                </button>
                            </header>
                            <form @submit.prevent = "onSubmit()">
                                <div class="bg-white px-4 pt-5 pb-4">
                                    <CustomInput class = "mb-2" v-model="product.title" label="Product Title"/>
                                    <CustomInput type = "file" class = "mb-2" label = "Product Image" @change = "file => product.image = file"/>
                                    <CustomInput type = "textarea" class="mb-2" v-model="product.description" label = "Description"/>
                                    <CustomInput type = "number" class="mb-2" v-model="product.price" label = "Price" prepend="Lei"/>

                                </div>
                                <footer class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse sm:justify-between">
                                    <button type="submit"
                                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-orange-500 text-base font-medium text-white hover:bg-orange-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 sm:w-auto sm:text-sm">
                                        Save
                                    </button>
                                    <button type="button"
                                            class="mt-3 w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-orange-500 text-base font-medium text-white hover:bg-orange-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 sm:mt-0 sm:w-auto sm:text-sm"
                                            @click="closeModal" ref="cancelButtonRef">
                                        Anulare
                                    </button>
                                </footer>


                            </form>






                        </DialogPanel>
                    </TransitionChild>
                </div>
            </div>
        </Dialog>
    </TransitionRoot>
</template>

<script setup>
import {computed, onUpdated, ref, defineProps, defineEmits} from 'vue'
import {
    TransitionRoot,
    TransitionChild,
    Dialog,
    DialogPanel,
    DialogTitle,
} from '@headlessui/vue'
import store from "../../store/index.js";
import CustomInput from "../../components/core/Table/CustomInput.vue";

const props = defineProps({
    modelValue: Boolean,
    product:{
        required: true,
        type: Object,
    }
})

const emit = defineEmits(['update:modelValue'])

const product = ref({
    id: props.product.id,
    title: props.product.title,
    image: props.product.image,
    description: props.product.description,
    price: props.product.price
})

const show = computed({
    get: ()=>props.modelValue,
    set:(value)=>emit('update:modelValue', value)
})

onUpdated(()=> {
    product.value = {
        id: props.product.id,
        title: props.product.title,
        image: props.product.image,
        description: props.product.description,
        price: props.product.price,
    }
})

function closeModal() {
    show.value = false
    emit('close')
}

function onSubmit() {
    if (product.value.id) {
        store.dispatch('updateProduct', product.value)
            .then(response => {
                if (response.status === 200) {
                    store.dispatch('getProducts', {
                        url: '/products',
                        search: '',
                        perPage: 10,
                        sort_field: '',
                        sort_direction: ''
                    });
                    closeModal();
                }
            })
            .catch(error => {
                console.error(error);
            });
    } else {
        store.dispatch('createProduct', product.value)
            .then(response => {
                if (response.status === 201) {
                    store.dispatch('getProducts', {
                        url: '/products', // Provide the URL parameter here
                        search: '',
                        perPage: 10,
                        sort_field: '',
                        sort_direction: ''
                    });
                    closeModal();
                }
            })
            .catch(error => {
                console.error(error);
            });
    }
}


</script>



<style scoped>
.disabled {
    pointer-events: none;
    opacity: 0.5;
}
</style>
