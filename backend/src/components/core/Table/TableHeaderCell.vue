<script setup>

const {field, sortField, sortDirection } = defineProps({
    field: String,
    sortField: String,
    sortDirection: String
})

const emit = defineEmits(['click'])

</script>

<template>
    <th @click = "emit('click', field)" class = "border-b-2 p-2 text-left cursor-pointer bg-gray-100"
    :class = "field === sortField ? 'bg-orange-80' : '' ">
        <div class = "flex justify-between">
            <slot></slot>
            <div v-if = "sortField === field" class = "ml-2">
                <svg v-if = "sortDirection === 'asc' " xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75 12 3m0 0 3.75 3.75M12 3v18" />
                </svg>
                <svg v-else xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 17.25 12 21m0 0-3.75-3.75M12 21V3" />
                </svg>


            </div>
        </div>
    </th>

</template>

<style scoped>

</style>
