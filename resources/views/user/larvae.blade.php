<x-user-layout>

    <main class="pt-8 pb-16 lg:pt-16 lg:pb-24 bg-white dark:bg-gray-900">
        <div class="flex justify-between px-4 mx-auto max-w-screen-xl ">
            <article class="mx-auto w-full max-w-3xl format format-sm sm:format-base lg:format-lg">
                <div class="text-xs 2xl:text-sm">
                    <div class="text-xs 2xl:text-sm">
                        <div class="xl:grid grid-cols-3 items-center">
                            <h2 class="col-span-2">
                                Visualizations of Larvae Data
                            </h2>
                            <div class="grid grid-cols-2 gap-x-4">
                                <x-select name="filterMapYear" id="filterMapYear" label="Tahun">
                                    @foreach (range(2002, date('Y')) as $year)
                                        <option value="{{ $year }}"
                                            @if ($year == date('Y')) selected @endif>
                                            {{ $year }}</option>
                                    @endforeach
                                </x-select>
                                <x-select name="filterMapRegency" id="filterMapRegency" label="Kabupaten/Kota">
                                    <option disabled selected value="">Pilih</option>
                                    @foreach ($regencies as $regency)
                                        <option value="{{ $regency->id }}">{{ $regency->name }}</option>
                                    @endforeach
                                </x-select>
                            </div>
                        </div>
                        <p class="leading-6 text-xs 2xl:text-sm mb-4">
                            We have collected samples of larvae presence, and we have found that the most common larvae
                            in our
                            area is the mosquito. You can see the data we have collected below.
                        </p>
                        <div id="mapContainer">
                            <div id="map" class="z-0 mb-4" style="height: 300px; border-radius: 6px"></div>
                        </div>
                        <p class="text-center text-xs 2xl:text-sm italic">
                            <span class="text-error">*</span>
                            This map shows the location of the samples collected by the user and it all have been
                            clustered to make it easier to see
                        </p>
                    </div>
                    <div class="xl:flex items-start justify-between gap-x-16">
                        <div>
                            <h2 class="bg-clip-text bg-gradient-to-r to-purple-500 from-purple-700 text-transparent">
                                Larvae Information</h2>
                            <p class="leading-7">
                                This is an immature life stage of an insect. But some people also use the term to
                                describe the early
                                life stages of fish, frogs or other animals. Usually, the larva looks very different
                                from the adult it
                                will become. A caterpillar, for example, doesn’t look much like a butterfly. The
                                larval stage of the
                                insect may also have completely different organs and structures than the adult, as
                                well as a different
                                diet. A frog larva has gills and breathes water, while the adult frog will come to
                                the surface to fill
                                its lungs with air.
                            </p>
                            <p class="leading-7">
                                Larvae (the plural of larva) are often adapted to very different environments than
                                they will live in
                                as adults. Adult mosquitoes are airborne, for instance. But their larvae hang out in
                                small pockets of
                                still water. There they gobble up algae and bacteria living on the water’s surface
                            </p>
                        </div>
                        <img src="{{ asset('assets/images/larvae/header.jpg') }}" alt=""
                            class="hidden xl:block w-32 h-32 object-cover rounded-xl">
                    </div>

                    <h3>Key identifiers of larval mosquitoes</h3>
                    <ol class="list-inside list-disc">
                        <li>
                            Large head and thorax; narrow, wormlike abdomen.
                        </li>
                        <li>
                            Hang just below the water surface, breathing air through tubes at the end of the abdomen.
                        </li>
                        <li>
                            When disturbed, they wriggle or squirm downward with jerking movements.
                        </li>
                        <li>
                            Pupal stage is comma-shaped; also hangs just under the water surface.
                        </li>
                        <li>
                            Aquatic, usually in still or stagnant water, including swampy areas, puddles, gutters, and
                            discarded car tires.
                        </li>
                    </ol>
                    <h3>Life Cycle</h3>
                    <p class="leading-7">
                        After a blood meal, females rest a few days and develop 100-400 or more eggs. These they usually
                        deposit on the water, flying close and tapping the abdomen onto the surface. Eggs hatch in a few
                        days and spend about a week as “wrigglers.” The pupal stage lasts 2-3 days, after which adults
                        emerge, climbing out onto the water surface. Adults mate within a few days, and females begin
                        seeking blood. The life cycle usually takes a few weeks, but when conditions are right, it can
                        take only 10 days.
                    </p>
                    <section class="space-x-3 flex text-xs 2xl:text-sm">
                        <div class="flex flex-col items-center">
                            <img class="w-20 h-20 rounded object-cover order-1"
                                src="{{ asset('assets/images/larvae/larva1.webp') }}" alt="Large avatar">
                        </div>
                        <div class="flex flex-col items-center">
                            <img class="w-20 h-20 rounded object-cover order-1"
                                src="{{ asset('assets/images/larvae/larva2.webp') }}" alt="Large avatar">
                        </div>
                        <div class="flex flex-col items-center">
                            <img class="w-20 h-20 rounded object-cover order-1"
                                src="{{ asset('assets/images/larvae/mosquito.webp') }}" alt="Large avatar">
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

    @push('js-internal')
        <!-- Map -->
        <script>
            let larvae = Object.values(@json($larvae));
            let lastLarvae = larvae[larvae.length - 1];
            let map = L.map('map').setView([lastLarvae.latitude, lastLarvae.longitude], 5);

            // full screen
            map.addControl(new L.Control.Fullscreen());

            let markers = L.markerClusterGroup();

            L.tileLayer(
                'https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token={accessToken}', {
                    attribution: '&copy; <a href="https://www.mapbox.com/">Mapbox</a> &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> <a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>',
                    maxZoom: 18,
                    id: 'mapbox/light-v11',
                    tileSize: 512,
                    zoomOffset: -1,
                    accessToken: 'pk.eyJ1IjoiaWJudTIyMDQyMiIsImEiOiJjbGltd3BkdnowMGpsM3JveGVteG52NWptIn0.Ficg1JfyGMJHRgnU48gDdg',
                }
            ).addTo(map);

            map.zoomControl.remove();

            larvae.forEach(larva => {
                let marker = L.marker([larva.latitude, larva.longitude], {
                    icon: L.divIcon({
                        // image
                        html: `<img src="{{ asset('assets/images/larvae/icon.jpg') }}" class="w-6 h-6">`,
                        className: 'text-white bg-transparent',
                        iconSize: [40, 40],
                        iconAnchor: [-10, 15],
                    })
                });
                marker.bindPopup(
                    `
                    <table>
                        <tr>
                            <td>Kabupaten/Kota</td>
                            <td>:</td>
                            <td>${larva.regency.name}</td>
                        </tr>
                        <tr>
                            <td>Kecamatan</td>
                            <td>:</td>
                            <td>${larva.district.name}</td>
                        </tr>
                    </table>
                    <table>
                        <tr>
                            <th>#</th>
                            <th>TPA</th>
                            <th>Larva</th>
                            <th>Telur</th>
                            <th>Nyamuk Dewasa</th>
                            <th>Suhu Air</th>
                            <th>Salinitas</th>
                            <th>PH</th>
                            <th>Tumbuhan Air</th>
                        </tr>
                    ` +
                    larva.detail_larvaes.map((data, index) => {
                        return `<tr>
                                    <td>${index + 1}</td>
                                    <td>${data.tpa_type.name}</td>
                                    <td>${data.amount_larva}</td>
                                    <td>${data.amount_egg}</td>
                                    <td>${data.number_of_adults}</td>
                                    <td>${data.water_temperature}</td>
                                    <td>${data.salinity}</td>
                                    <td>${data.ph}</td>
                                    <td>${data.aquatic_plant == 'available' ? 'Ada' : 'Tidak Ada'}</td>
                                </tr>`
                    }).join('') +
                    `
                        </table>`
                    // adjust width popup
                ).on('popupopen', function() {
                    $('.leaflet-popup-content').width('auto');
                });

                // on click marker
                marker.on('click', function() {
                    map.setView([larva.latitude, larva.longitude], 15);
                });

                markers.addLayer(marker);
            });

            map.addLayer(markers);

            $(function() {
                $('#filterMapYear').change(function() {
                    let year = $(this).val();
                    $.ajax({
                        type: "POST",
                        url: "{{ route('user.larvae.filter-map-year') }}",
                        data: {
                            _token: "{{ csrf_token() }}",
                            year: year
                        },
                        success: function(response) {
                            larvae = Object.values(response.larvae);
                            markers.clearLayers();
                            if (larvae.length > 0) {
                                lastLarvae = larvae[larvae.length - 1];
                                map.setView([lastLarvae.latitude, lastLarvae.longitude], 5);
                                larvae.forEach(larva => {
                                    let marker = L.marker([larva.latitude, larva
                                        .longitude
                                    ], {
                                        icon: L.divIcon({
                                            // image
                                            html: `<img src="{{ asset('assets/images/larvae/icon.jpg') }}" class="w-6 h-6">`,
                                            className: 'text-white bg-transparent',
                                            iconSize: [40, 40],
                                            iconAnchor: [-10, 15],
                                        })
                                    });
                                    marker.bindPopup(
                                        `
                                        <table>
                                            <tr>
                                                <td>Kabupaten/Kota</td>
                                                <td>:</td>
                                                <td>${larva.regency.name}</td>
                                            </tr>
                                            <tr>
                                                <td>Kecamatan</td>
                                                <td>:</td>
                                                <td>${larva.district.name}</td>
                                            </tr>
                                        </table>
                                        <table>
                                        <tr>
                                            <th>#</th>
                                            <th>TPA</th>
                                            <th>Larva</th>
                                            <th>Telur</th>
                                            <th>Nyamuk Dewasa</th>
                                            <th>Suhu Air</th>
                                            <th>Salinitas</th>
                                            <th>PH</th>
                                            <th>Tumbuhan Air</th>
                                        </tr>
                                    ` +
                                        larva.detail_larvaes.map((data, index) => {
                                            return `<tr>
                                                    <td>${index + 1}</td>
                                                    <td>${data.tpa_type.name}</td>
                                                    <td>${data.amount_larva}</td>
                                                    <td>${data.amount_egg}</td>
                                                    <td>${data.number_of_adults}</td>
                                                    <td>${data.water_temperature}</td>
                                                    <td>${data.salinity}</td>
                                                    <td>${data.ph}</td>
                                                    <td>${data.aquatic_plant == 'available' ? 'Ada' : 'Tidak Ada'}</td>
                                                </tr>`
                                        }).join('') +
                                        `
                                        </table>`
                                        // adjust width popup
                                    ).on('popupopen', function() {
                                        $('.leaflet-popup-content').width('auto');
                                    });

                                    // on click marker
                                    marker.on('click', function() {
                                        map.setView([larva.latitude, larva
                                                .longitude
                                            ],
                                            15);
                                    });

                                    markers.addLayer(marker);
                                });

                                map.addLayer(markers);
                            }
                        }
                    });
                });

                $('#filterMapRegency').change(function() {
                    let regency_id = $(this).val();
                    $.ajax({
                        type: "POST",
                        url: "{{ route('user.larvae.filter-map-regency') }}",
                        data: {
                            _token: "{{ csrf_token() }}",
                            regency_id: regency_id
                        },
                        success: function(response) {
                            larvae = Object.values(response.larvae);
                            markers.clearLayers();
                            if (larvae.length > 0) {
                                lastLarvae = larvae[larvae.length - 1];
                                map.setView([lastLarvae.latitude, lastLarvae.longitude], 5);
                                larvae.forEach(larva => {
                                    let marker = L.marker([larva.latitude, larva
                                        .longitude
                                    ], {
                                        icon: L.divIcon({
                                            // image
                                            html: `<img src="{{ asset('assets/images/larvae/icon.jpg') }}" class="w-6 h-6">`,
                                            className: 'text-white bg-transparent',
                                            iconSize: [40, 40],
                                            iconAnchor: [-10, 15],
                                        })
                                    });
                                    marker.bindPopup(
                                        `
                                        <table>
                                            <tr>
                                                <td>Kabupaten/Kota</td>
                                                <td>:</td>
                                                <td>${larva.regency.name}</td>
                                            </tr>
                                            <tr>
                                                <td>Kecamatan</td>
                                                <td>:</td>
                                                <td>${larva.district.name}</td>
                                            </tr>
                                        </table>

                                        <table>
                                        <tr>
                                            <th>#</th>
                                            <th>TPA</th>
                                            <th>Larva</th>
                                            <th>Telur</th>
                                            <th>Nyamuk Dewasa</th>
                                            <th>Suhu Air</th>
                                            <th>Salinitas</th>
                                            <th>PH</th>
                                            <th>Tumbuhan Air</th>
                                        </tr>
                                    ` +
                                        larva.detail_larvaes.map((data, index) => {
                                            return `<tr>
                                                    <td>${index + 1}</td>
                                                    <td>${data.tpa_type.name}</td>
                                                    <td>${data.amount_larva}</td>
                                                    <td>${data.amount_egg}</td>
                                                    <td>${data.number_of_adults}</td>
                                                    <td>${data.water_temperature}</td>
                                                    <td>${data.salinity}</td>
                                                    <td>${data.ph}</td>
                                                    <td>${data.aquatic_plant == 'available' ? 'Ada' : 'Tidak Ada'}</td>
                                                </tr>`
                                        }).join('') +
                                        `
                                        </table>`
                                        // adjust width popup
                                    ).on('popupopen', function() {
                                        $('.leaflet-popup-content').width('auto');
                                    });

                                    // on click marker
                                    marker.on('click', function() {
                                        map.setView([larva.latitude, larva
                                                .longitude
                                            ],
                                            15);
                                    });

                                    markers.addLayer(marker);
                                });

                                map.addLayer(markers);
                            }
                        }
                    });
                });
            });
        </script>
    @endpush
</x-user-layout>
