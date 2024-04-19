<template>
    <div class="transition-all min-h-screen bg-gray-200 flex">
        <!-- Sidebar -->
        <Sidebar :class="{'-ml-[200px]': !sidebarOpened}"/>
        <div class="flex-1">
            <!-- Header -->
            <Navbar @toggle-sidebar="toggleSidebar"/>
            <!-- Content -->
            <main class = "p-6">
                <div class = "p-4 rounded bg-white">
                    <router-view></router-view>
                </div>
            </main>
        </div>
    </div>
</template>

<script setup>
import {ref, onMounted, onUnmounted} from'vue'
import Sidebar from "./Sidebar.vue";
import Navbar from "./Navbar.vue";
import store from "../store/index.js";

const { title } = defineProps({
    title: String
});

const sidebarOpened = ref(true);
function toggleSidebar(){
    sidebarOpened.value = !sidebarOpened.value
}

onMounted(() => {
    store.dispatch('getUser');
    handleSidebarOpened();
    window.addEventListener('resize', handleSidebarOpened)
})
onUnmounted(() => {
    window.removeEventListener('resize', handleSidebarOpened)
})
function handleSidebarOpened(){
    sidebarOpened.value = window.outerWidth > 768;

}
</script>

<style scoped>
.flex-1 {
    /* Asigură ca elementul ocupă întreaga înălțime disponibilă */
    height: 100%;
}
</style>
