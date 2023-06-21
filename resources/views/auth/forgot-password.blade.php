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
                    <div class="mb-4 text-xs 2xl:text-sm text-gray-600">
                        {{ __('Lupa password? Silahkan isi email kamu, kami akan melakukan verifikasi untuk mengganti password kamu.') }}
                    </div>
                    <form class="space-y-4 md:space-y-6" action="{{ route('admin.user.check-email') }}" method="POST">
                        @csrf
                        <x-input id="email" label="Email" name="email" type="text" required />
                        <div class="flex flex-col gap-3 md:flex-row md:justify-between mt-6 items-center">
                            <a href="{{ route('login') }}"
                                class="text-xs 2xl:text-sm font-medium text-gray-500 hover:underline">Kembali</a>
                            <x-button type="submit" class="bg-primary">
                                {{ __('Verifikasi Email') }}
                            </x-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    @push('js-internal')
        <script>
            $(function () {
                @if (Session::has('error'))
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: '{{ Session::get('error') }}',
                    })
                @endif
            });
        </script>
    @endpush
</x-guest-layout>
