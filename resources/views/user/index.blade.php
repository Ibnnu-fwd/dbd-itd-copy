<x-user-layout>
    <!-- Hero -->
    <section class="">
        <div class="grid max-w-screen-xl py-7 mx-auto lg:gap-8 xl:gap-14 lg:py-16 lg:grid-cols-12">
            <div class="mr-auto place-self-center lg:col-span-6">
                <h1
                    class="max-w-2xl mb-4 text-4xl font-extrabold tracking-tight leading-none md:text-4xl xl:text-5xl dark:text-white">
                    <span
                        class="text-transparent bg-clip-text bg-gradient-to-r to-purple-500 from-purple-700">Streamlined
                        Vector,</span> <br>Monitoring System
                </h1>
                <p class="max-w-2xl mb-6 text-gray-500 lg:mb-8 md:text-sm lg:text-md dark:text-gray-400">
                    Collecting data from various sources and displaying it in a single dashboard is a challenge. We are
                    here to help you.
                </p>
                <x-link-button route="{{ route('login') }}" class="bg-primary">
                    Get Started
                </x-link-button>
            </div>
            <div class="hidden lg:mt-0 lg:col-span-6 lg:flex">
                <img src="{{ asset('assets/images/hero2.png') }}" class="h-full w-full" alt="mockup">
            </div>
        </div>
    </section>

    <!-- Features -->
    <section class="">
        <div class="py-6 px-4 mx-auto max-w-screen-xl sm:py-16 lg:px-6">
            <div class="space-y-8 md:grid md:grid-cols-2 lg:grid-cols-3 md:gap-12 md:space-y-0">
                <div class="md:col-span-1">
                    <div class="flex justify-center items-center mb-4 w-10 h-10 rounded-md bg-purple-600 lg:h-8 lg:w-8">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="1.5"
                            viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 12.75L11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 01-1.043 3.296 3.745 3.745 0 01-3.296 1.043A3.745 3.745 0 0112 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 01-3.296-1.043 3.745 3.745 0 01-1.043-3.296A3.745 3.745 0 013 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 011.043-3.296 3.746 3.746 0 013.296-1.043A3.746 3.746 0 0112 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 013.296 1.043 3.746 3.746 0 011.043 3.296A3.745 3.745 0 0121 12z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="mb-2 text-sm 2xl:text-md font-bold dark:text-white">Integrity</h3>
                    <p class="text-gray-500 dark:text-gray-400 text-xs 2xl:text-sm leading-5">
                        Every data that we collect is guaranteed to be accurate and reliable. We also provide a data log
                        to
                        ensure that the data is not manipulated.
                    </p>
                </div>
                <div class="md:col-span-1">
                    <div
                        class="flex justify-center items-center mb-4 w-10 h-10 rounded-md bg-purple-600 lg:h-8 lg:w-8 dark:bg-primary-900">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="1.5"
                            viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M21 12a2.25 2.25 0 00-2.25-2.25H15a3 3 0 11-6 0H5.25A2.25 2.25 0 003 12m18 0v6a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 18v-6m18 0V9M3 12V9m18 0a2.25 2.25 0 00-2.25-2.25H5.25A2.25 2.25 0 003 9m18 0V6a2.25 2.25 0 00-2.25-2.25H5.25A2.25 2.25 0 003 6v3">
                            </path>
                        </svg>
                    </div>
                    <h3 class="mb-2 text-sm 2xl:text-md font-bold dark:text-white">Efficient</h3>
                    <p class="text-gray-500 dark:text-gray-400 text-xs 2xl:text-sm leading-5">
                        We provide a dashboard that is easy to understand and use. You can also access it from anywhere
                        and anytime
                    </p>
                </div>
                <div class="md:col-span-1">
                    <div
                        class="flex justify-center items-center mb-4 w-10 h-10 rounded-md bg-purple-600 lg:h-8 lg:w-8 dark:bg-primary-900">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="1.5"
                            viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 18v-5.25m0 0a6.01 6.01 0 001.5-.189m-1.5.189a6.01 6.01 0 01-1.5-.189m3.75 7.478a12.06 12.06 0 01-4.5 0m3.75 2.383a14.406 14.406 0 01-3 0M14.25 18v-.192c0-.983.658-1.823 1.508-2.316a7.5 7.5 0 10-7.517 0c.85.493 1.509 1.333 1.509 2.316V18">
                            </path>
                        </svg>
                    </div>
                    <h3 class="mb-2 text-sm 2xl:text-md font-bold dark:text-white">
                        Sychronized
                    </h3>
                    <p class="text-gray-500 dark:text-gray-400 text-xs 2xl:text-sm leading-5">
                        Every data that we collect is synchronized with the server. So you dont have to worry about
                        losing data.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <section class="">
        <div class="pt-12 pb-6 px-4 mx-auto max-w-screen-xl sm:py-16 lg:px-6">
            <div class="mx-auto max-w-screen-sm text-center">
                <h2 class="mb-4 text-3xl tracking-tight font-extrabold leading-tight text-gray-900 dark:text-white">
                    What We're Building
                </h2>
                <p class="mb-6 text-gray-500 dark:text-gray-400 md:text-sm">
                    We are a team of passionate people whose goal is to improve everyone's life and give you the
                    confidence to monitor your data.
                </p>
            </div>
        </div>
    </section>

    <!-- CTA -->

    <!-- MOSQUITO -->
    <section class="">
        <div
            class="gap-8 items-center py-8 px-4 mx-auto max-w-screen-xl xl:gap-16 md:grid md:grid-cols-2 sm:py-16 lg:px-6">
            <img class="w-full dark:hidden object-cover h-72 rounded-lg" src="{{ asset('assets/images/mosquito.jpg') }}"
                alt="abj image">
            <div class="mt-4 md:mt-0">
                <h2 class="mb-4 text-2xl tracking-tight font-extrabold text-gray-900 dark:text-white">
                    Mosquito Vector <br>Distribution Data
                </h2>
                <p class="mb-6 text-gray-500 md:text-xs leading-5">
                    We already have a database of mosquito distribution data in the area that was collected by our team
                    and the community. Hope you can know the distribution of mosquitoes based on type, location and
                    other variables.
                </p>
                <x-link-button route="{{ route('user.vector') }}" color="gray">
                    Learn More
                </x-link-button>
            </div>
        </div>
    </section>

    <!-- Larva -->
    <section class="">
        <div
            class="gap-8 items-center py-8 px-4 mx-auto max-w-screen-xl xl:gap-16 md:grid md:grid-cols-2 sm:py-16 lg:px-6">
            <div class="mt-4 md:mt-0">
                <h2 class="mb-4 text-2xl tracking-tight font-extrabold text-gray-900 dark:text-white">
                    Larval Dispersal <br>Data
                </h2>
                <p class="mb-6 text-gray-500 md:text-xs leading-5">
                    We have been collecting the existence of larvae in the area grouped by the type of larvae, district,
                    and village. So you can know the number of larvae in your area and take action to eradicate them.
                </p>
                <x-link-button route="#" color="gray">
                    Learn More
                </x-link-button>
            </div>
            <img class="w-full dark:hidden object-cover h-72 rounded-lg" src="{{ asset('assets/images/larva.jpg') }}"
                alt="larva image">
        </div>
    </section>

    <!-- ABJ -->
    <section class="">
        <div
            class="gap-8 items-center py-8 px-4 mx-auto max-w-screen-xl xl:gap-16 md:grid md:grid-cols-2 sm:py-16 lg:px-6">
            <img class="w-full dark:hidden object-cover h-72 rounded-lg" src="{{ asset('assets/images/abj.jpg') }}"
                alt="abj image">
            <div class="mt-4 md:mt-0">
                <h2 class="mb-4 text-2xl tracking-tight font-extrabold text-gray-900 dark:text-white">
                    Visualization of <br>Larva-Free Counts
                </h2>
                <p class="mb-6 text-gray-500 md:text-xs leading-5">
                    We have been collecting the existence of mosquitoes in the area grouped by the type of mosquitoes,
                    and counted the number of mosquitoes that are free of larvae
                </p>
                <x-link-button route="#" color="gray">
                    Learn More
                </x-link-button>
            </div>
        </div>
    </section>
</x-user-layout>
