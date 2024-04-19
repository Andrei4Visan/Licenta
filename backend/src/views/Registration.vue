<template>
    <div class="flex min-h-full items-center justify-center px-6 py-12 lg:px-8">
        <div class="sm:mx-auto sm:w-full sm:max-w-sm">
            <h2 class="mt-8 text-center text-2xl font-bold leading-9 tracking-tight text-white">Set new password</h2>
        </div>

        <div class="mt-6 sm:mx-auto sm:w-full sm:max-w-sm">
            <form class="space-y-6" action="#" method="POST">
                <div>
                    <label for="password" class="block text-sm font-medium leading-6 text-white">New password</label>
                    <div class="mt-2">
                        <input id="password" name="password" type="password" autocomplete="new-password" required="" class="block w-full rounded-md border border-gray-300 py-1.5 text-gray-900 shadow-sm focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                    </div>
                </div>

                <div>
                    <label for="confirm-password" class="block text-sm font-medium leading-6 text-white">Confirm password</label>
                    <div class="mt-2">
                        <input id="confirm-password" name="confirm-password" type="password" autocomplete="new-password" required="" class="block w-full rounded-md border border-gray-300 py-1.5 text-gray-900 shadow-sm focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                    </div>
                </div>

                <div>
                    <button type="submit" class="flex w-full justify-center rounded-md bg-indigo-600 px-3 py-1.5 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Set password</button>
                </div>
            </form>

            <p class="mt-10 text-center text-sm text-white">
                <router-link :to="{name: 'login' }" class="font-semibold text-indigo-600 hover:text-indigo-500">Back to sign in</router-link>
            </p>
        </div>
    </div>
</template>

<style scoped>
/* Remove the default background color */
body {
    background-color: inherit;
}

/* Add a gradient background */
html {
    background-image: linear-gradient(to right, #5677fc, #382374);
}
</style>
