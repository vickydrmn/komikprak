<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="block mt-1 w-full"
                type="password"
                name="password"
                required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                type="password"
                name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>
        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>
            <x-primary-button class="ms-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                document.getElementById("name").oninvalid = function(event) {
                    if (event.target.value === "") {
                        event.target.setCustomValidity("Nama harus diisi. Silakan masukkan nama Anda.");
                    } else {
                        event.target.setCustomValidity("Nama tidak valid. Silakan cek kembali.");
                    }
                };
                document.getElementById("email").oninvalid = function(event) {
                    if (event.target.value === "") {
                        event.target.setCustomValidity("Email harus diisi. Silakan masukkan alamat email yang valid.");
                    } else {
                        event.target.setCustomValidity("Format email tidak valid. Silakan masukkan email yang benar.");
                    }
                };
                document.getElementById("password").oninvalid = function(event) {
                    if (event.target.value === "") {
                        event.target.setCustomValidity("Password harus diisi. Silakan masukkan password Anda.");
                    } else {
                        event.target.setCustomValidity("Password tidak valid. Pastikan memenuhi kriteria yang diminta.");
                    }
                };
                document.getElementById("password_confirmation").oninvalid = function(event) {
                    if (event.target.value === "") {
                        event.target.setCustomValidity("Konfirmasi password harus diisi. Silakan masukkan konfirmasi password.");
                    } else {
                        event.target.setCustomValidity("Konfirmasi password tidak sesuai. Silakan ulangi.");
                    }
                };
                document.querySelectorAll("input").forEach(function(input) {
                    input.oninput = function(event) {
                        event.target.setCustomValidity("");
                    };
                });
            });
        </script>
    </form>
</x-guest-layout>