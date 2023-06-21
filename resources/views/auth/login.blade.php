<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <section class="bg-gray-50">
        <div class="flex flex-col items-center justify-center px-6 py-8 mx-auto md:h-screen lg:py-0">
            <a href="/" class="flex items-center text-2xl mb-6 font-semibold text-gray-900 dark:text-white">
                <img class="w-full h-24 mr-2 hidden md:block" src="{{ asset('assets/images/logo.png') }}" alt="logo">
            </a>

            <div
                class="w-full bg-white rounded-lg shadow dark:border md:mt-0 sm:max-w-md xl:p-0 dark:bg-gray-800 dark:border-gray-700">
                <div class="p-6 space-y-4 md:space-y-6 sm:p-8">
                    <form class="space-y-4 md:space-y-6" action="{{ route('login') }}" method="POST">
                        @csrf
                        <x-input id="email" label="Email" name="email" type="email" required />
                        <x-input id="password" label="Kata sandi" name="password" type="password" required />
                        <div class="gap-3 md:flex-row md:justify-between mt-6 items-center">
                            <x-button type="submit" class="bg-primary justify-center w-full md:w-full">
                                Masuk
                            </x-button>
                            <div class="block md:flex items-center justify-end mt-4">
                                {{-- <a href="{{ route('register') }}"
                                    class="text-xs 2xl:text-sm font-medium text-gray-500 hover:underline">Belum punya
                                    akun?</a> --}}
                                <a href="{{ route('password.request') }}"
                                    class="text-xs 2xl:text-sm font-medium text-gray-500 hover:underline">Lupa kata
                                    sandi?</a>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </section>

    @push('js-internal')
        <script>
            @if (Session::has('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: '{{ Session::get('success') }}',
                })
            @endif

            @if (Session::has('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: '{{ Session::get('error') }}',
                })
            @endif
        </script>
    @endpush
</x-guest-layout>
