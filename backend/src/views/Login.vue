<template>
    <GuestLayout title="Sign in to your account">
        <form class="space-y-6"  method="POST" @submit.prevent = "login">
            <div v-if="errorMessage" class="flex items-center justify-between py-3 px-5 bg-red-500 text-white-rounded">
                {{errorMessage}}
                <span @click="errorMessage = ''"class="w-8 h-8 flex items-center justify-center rounded-full transition-colors cursor-pointer hover:bg-black/20">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
  <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
</svg>
                </span>
            </div>
            <div>
                <label for="email" class="block text-sm font-medium leading-6 text-gray-900">Email address</label>
                <div class="mt-2">
                    <input id="email" name="email" type="email" autocomplete="email" required="" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" v-model="user.email" />
                </div>
            </div>

            <div>
                <div class="flex items-center justify-between">
                    <label for="password" class="block text-sm font-medium leading-6 text-gray-900">Password</label>
                    <div class="text-sm">
                        <router-link :to="{name: 'requestPassword'}" class="font-semibold text-orange-600 hover:text-orange-500">Forgot password?</router-link>
                    </div>
                </div>
                <div class="mt-2">
                    <input id="password" name="password" type="password" autocomplete="current-password" required="" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-orange-600 sm:text-sm sm:leading-6" v-model="user.password" />
                </div>
            </div>

            <div>
                <button type="submit" :disabled="loading" class="flex w-full justify-center rounded-md bg-orange-600 px-3 py-1.5 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-orange-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-orange-600"
                :class="{
                    'cursor-not-allowed': loading,
                    'hover:bg-orange-800': loading
                }">
                    <svg
                        v-if="loading"
                        class="animate-spin -ml-1 mr-3 h-5 w-5 text-white"
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                    >
                        <circle
                            class="opacity-25"
                            cx="12"
                            cy="12"
                            r="10"
                            stroke="currentColor"
                            stroke-width="4"
                        ></circle>
                        <path
                            class="opacity-75"
                            fill="currentColor"
                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                        ></path>
                    </svg>Sign in</button>
            </div>
        </form>
    </GuestLayout>

</template>

<style scoped>

</style>
<script setup >
import {ref} from 'vue'
import GuestLayout from "../components/GuestLayout.vue";
import {useRouter} from "vue-router";
import store from "../store/index.js";
// import router from "../router/index.js";
const router = useRouter()

let loading = ref(false);
let errorMessage = ref("");

const user = {
    email:'',
    password:'',
    // remember: false
}
function login(){
    loading.value = true;
    store.dispatch('login', user)
        .then( ()=>{
        router.push({name:'app.dashboard'})
    })
        .catch(({response})=>{
            loading.value=false;
            errorMessage.value = response.data.message;
        })

}
</script>
