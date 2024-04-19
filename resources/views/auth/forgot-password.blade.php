<x-app-layout>
    <form action="{{route('password.email')}}" method="post" class="w-[400px] mx-auto p-6 my-16">
        @csrf
        <h2 class="text-2xl font-semibold text-center mb-5">
            Introdu adresa de mail pentru a-ți putea reseta parola
        </h2>
        <p class="text-center text-gray-500 mb-6">
            sau
            <a
                href="{{route('login')}}"
                class="text-orange-600 hover:text-orange-500"
            >loghează-te în contul tău existent</a
            >
        </p>

        <x-auth-session-status class="mb-4" :status="session('status')" />

        <x-auth-validation-errors class="mb-4" :errors="$errors"></x-auth-validation-errors>

        <div class="mb-3">
            <input
                id="loginEmail"
                type="email"
                name="email"
                placeholder="Adresa ta de mail"
                class="border-gray-300 focus:border-orange-500 focus:outline-none focus:ring-orange-500 rounded-md w-full"
            />
        </div>
        <button
            class="btn-primary bg-emerald-500 hover:bg-emerald-600 active:bg-emerald-700 w-full"
        >
            Confirm
        </button>
    </form>
{{--    <div class="mb-4 text-sm text-gray-600">--}}
{{--        {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}--}}
{{--    </div>--}}

    <!-- Session Status -->


{{--    <form method="POST" action="{{ route('password.email') }}">--}}
{{--        @csrf--}}

{{--        <!-- Email Address -->--}}
{{--        <div>--}}
{{--            <x-input-label for="email" :value="__('Email')" />--}}
{{--            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />--}}
{{--            <x-input-error :messages="$errors->get('email')" class="mt-2" />--}}
{{--        </div>--}}

{{--        <div class="flex items-center justify-end mt-4">--}}
{{--            <x-primary-button>--}}
{{--                {{ __('Email Password Reset Link') }}--}}
{{--            </x-primary-button>--}}
{{--        </div>--}}
{{--    </form>--}}
</x-app-layout>
