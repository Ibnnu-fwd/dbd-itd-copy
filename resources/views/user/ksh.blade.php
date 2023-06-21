<x-user-layout>

    <main class="pt-8 pb-16 lg:pt-16 lg:pb-24 bg-white dark:bg-gray-900">
        <div class="flex justify-between px-4 mx-auto max-w-screen-xl ">
            <article class="mx-auto w-full max-w-3xl format format-sm sm:format-base lg:format-lg">
                <div class="text-xs 2xl:text-sm">
                    <div class="xl:flex items-start justify-between gap-x-16">
                        <div>
                            <h2 class="from-blue-400 to-purple-700 bg-gradient-to-r bg-clip-text text-transparent">
                                Surabaya Great Cadre</h2>
                            <p class="leading-7">
                                Kader Surabaya Hebat is an award or title given to cadres or volunteers in Surabaya who
                                have made outstanding contributions in advancing and improving the quality of life of
                                the people of Surabaya. The title recognizes their success and dedication in various
                                fields, including health, education, environment, community empowerment, and so on.
                            </p>
                            <p class="leading-7">
                                Surabaya Hebat cadres have generally demonstrated strong commitment and exceptional hard
                                work in carrying out their duties. They work with passion, collaborate with various
                                parties, and strive to make a positive difference in their communities.
                            </p>
                        </div>
                        <img src="{{ asset('assets/images/ksh/header.jpg') }}" alt=""
                            class="hidden xl:block w-32 h-32 object-cover rounded-xl">
                    </div>

                    <h3>Responsibility</h3>
                    <ol class="list-inside list-disc">
                        <li class="leading-7">
                            Increase the health awareness of the Surabaya community through counseling, campaigns, and
                            educational activities on healthy lifestyles, disease prevention, and health promotion.
                        </li>
                        <li class="leading-7">
                            Raising funds or organizing charity activities to help communities in need, such as orphans,
                            the elderly, or marginalized community groups.
                        </li>
                        <li class="leading-7">
                            Organizing training and workshops to empower communities in various fields, such as life
                            skills, entrepreneurship, agriculture, or creative industries.
                        </li>
                        <li class="leading-7">
                            Engage in environmental conservation campaigns and keep the city clean, such as tree
                            planting programs, waste management, or single-use plastic reduction.
                        </li>
                    </ol>
                    <h3>Activities</h3>
                    <p class="leading-7">
                        The title of Kader Surabaya Hebat is a form of appreciation from the Surabaya City Government
                        and the community for the outstanding efforts and achievements of the cadres in building a
                        better Surabaya. This title can also motivate and inspire others to contribute and become agents
                        of change in their communities.
                    </p>
                    <section class="space-x-3 flex text-xs 2xl:text-sm">
                        <div class="flex flex-col items-center">
                            <img class="w-20 h-20 rounded object-cover order-1"
                                src="{{ asset('assets/images/ksh/ksh1.jpg') }}" alt="Large avatar">
                        </div>
                        <div class="flex flex-col items-center">
                            <img class="w-20 h-20 rounded object-cover order-1"
                                src="{{ asset('assets/images/ksh/ksh2.jpg') }}" alt="Large avatar">
                        </div>
                        <div class="flex flex-col items-center">
                            <img class="w-20 h-20 rounded object-cover order-1"
                                src="{{ asset('assets/images/ksh/ksh3.jpeg') }}" alt="Large avatar">
                        </div>
                    </section>
                </div>

                <p class="leading-6 text-xs 2xl:text-sm">
                    We have collected samples of larvae from different places and have analyzed them. The data is shown
                    in the form of graphs and charts below.
                </p>

                <div class="text-xs 2xl:text-sm">
                    <h3>
                        Visualizations of Larvae Data
                    </h3>
                </div>
            </article>
        </div>
    </main>
</x-user-layout>
