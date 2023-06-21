<x-app-layout>
    <x-breadcrumb name="variable-agent.show" :data="$regency" />
    <x-card-container>
        <div class="lg:flex gap-x-3 mb-4">
            <x-select id="filterType">
                <option value="month" selected>Bulanan</option>
                <option value="date">Tanggal</option>
            </x-select>
            <div id="filterMonth">
                <x-select id="monthOptionFilter">
                    <option value="Pilih Bulan" disabled selected>Pilih Bulan</option>
                    @foreach ($months as $month)
                        <option value="{{ $month }}">{{ $month }}</option>
                    @endforeach
                </x-select>
            </div>
            <div id="filterRange" class="items-center hidden">
                <div date-rangepicker class="sm:flex sm:space-x-4 items-center">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <svg aria-hidden="true" class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="currentColor"
                                viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd"
                                    d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"
                                    clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <input name="startDate" type="text"
                            class="border border-gray-300 text-gray-900 text-xs 2xl:text-sm rounded-lg focus:ring-primary focus:border-primary block w-full pl-10 p-3 mb-1.5"
                            placeholder="Pilih tanggal mulai" autocomplete="off">
                    </div>
                    <span class="mx-4 text-gray-500 text-xs 2xl:text-sm mb-2">sampai</span>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <svg aria-hidden="true" class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="currentColor"
                                viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd"
                                    d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"
                                    clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <input name="endDate" type="text"
                            class="border border-gray-300 text-gray-900 text-xs 2xl:text-sm rounded-lg focus:ring-primary focus:border-primary block w-full pl-10 p-3 mb-1.5"
                            placeholder="Pilih tanggal berakhir" autocomplete="off">
                    </div>
                    <x-link-button id="btnFilter" color="gray" class="py-2.5 mb-1.5">
                        Filter
                    </x-link-button>
                </div>
            </div>
        </div>
        <div id="map" class="z-0 mb-4" style="height: 280px; border-radius: 6px"></div>
        <table id="variableAgentTable" class="w-full">
            <thead>
                <tr>
                    <th>Kecamatan</th>
                    <th>Lokasi</th>
                    <th>Jumlah</th>
                    <th>Tipe</th>
                    <th>Tanggal</th>
                </tr>
            </thead>
        </table>
    </x-card-container>

    @push('js-internal')
        <script>
            $(function() {
                $('#variableAgentTable').DataTable({
                    processing: true,
                    serverSide: true,
                    responsive: true,
                    autoWidth: false,
                    ajax: "{{ route('admin.variable-agent.show', $id) }}",
                    columns: [{
                            data: 'district',
                            name: 'district'
                        },
                        {
                            data: 'location',
                            name: 'location'
                        },
                        {
                            data: 'count',
                            name: 'count'
                        },
                        {
                            data: 'type',
                            name: 'type'
                        },
                        {
                            data: 'created_at',
                            name: 'created_at'
                        },
                    ]
                });

                let samples = Object.values(@json($samples));
                let map = L.map('map').setView([-8.172357, 113.699948], 10);
                console.log(samples);

                let markers = L.markerClusterGroup();

                // full screen
                map.addControl(new L.Control.Fullscreen());

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
                samples.map((sample) => {
                    let marker = L.marker([sample.latitude, sample.longitude], {
                        icon: L.divIcon({
                            // using image
                            html: `<img src="{{ asset('assets/images/vector/mosquito-icon.png') }}" class="w-6 h-6">`,
                            backgroundSize: 'contain',
                            className: 'marker bg-transparent',
                            iconAnchor: [15, 15],
                            popupAnchor: [0, -15]
                        })
                    });
                    marker.bindPopup(`
                        <table class="table-auto">
                            <tr>
                                <td>Kabupaten/Kota</td>
                                <td>:</td>
                                <td>${sample.regency}</td>
                            </tr>
                            <tr>
                                <td>Kecamatan</td>
                                <td>:</td>
                                <td>${sample.district}</td>
                            </tr>
                            <tr>
                                <td>Latitude</td>
                                <td>:</td>
                                <td>${sample.latitude}</td>
                            </tr>
                            <tr>
                                <td>Longitude</td>
                                <td>:</td>
                                <td>${sample.longitude}</td>
                            </tr>
                            <tr>
                                <td>Jumlah Sampel</td>
                                <td>:</td>
                                <td>${sample.count}</td>
                            </tr>
                            <tr>
                                <td>Tipe</td>
                                <td>:</td>
                                <td>
                                    <table class="table-auto">
                                        ${
                                            sample.type.map(type => {
                                                return `
                                                                                                                    <tr>
                                                                                                                        <td>${type.name}</td>
                                                                                                                        <td>:</td>
                                                                                                                        <td>${type.amount}</td>
                                                                                                                    </tr>
                                                                                                                `;
                                            }).join('')
                                        }
                                    </table>
                                </td>
                            </tr>
                        </table>
                    `);

                    // on click
                    marker.on('click', function(e) {
                        map.setView(e.latlng, 12);
                    });

                    markers.addLayer(marker);
                });

                map.addLayer(markers);

                // filter
                $('#filterType').on('change', function() {
                    let val = $(this).val();
                    if (val == 'date') {
                        $('#filterRange').removeClass('hidden').addClass('flex');
                        $('#filterMonth').addClass('hidden')
                    } else {
                        $('#filterRange').addClass('hidden').removeClass('flex');
                        $('#filterMonth').removeClass('hidden');
                    }
                });

                // filter month
                $('#monthOptionFilter').change(function(e) {
                    e.preventDefault();
                    let index = $(this).prop('selectedIndex');
                    $.ajax({
                        url: "{{ route('admin.variable-agent.show.filter-month', ':id') }}".replace(
                            ':id', "{{ $id }}"),
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            month: index
                        },
                        success: function(response) {
                            $('#variableAgentTable').DataTable().clear().destroy();
                            $('#variableAgentTable').DataTable({
                                processing: true,
                                responsive: true,
                                autoWidth: false,
                                data: response.data,
                                columns: [{
                                        data: 'district',
                                        name: 'district'
                                    },
                                    {
                                        data: 'location',
                                        name: 'location'
                                    },
                                    {
                                        data: 'count',
                                        name: 'count'
                                    },
                                    {
                                        data: 'type',
                                        name: 'type'
                                    },
                                    {
                                        data: 'created_at',
                                        name: 'created_at'
                                    },
                                ]
                            });

                            if (response.data.length > 0) {
                                // remove layer marker
                                map.removeLayer(markers);
                                markers = L.markerClusterGroup();
                                samples = Object.values(response.samples);
                                console.log(response.samples);

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

                                samples.map((sample) => {
                                    let marker = L.marker([sample.latitude, sample
                                        .longitude
                                    ], {
                                        icon: L.divIcon({
                                            // using image
                                            html: `<img src="{{ asset('assets/images/vector/mosquito-icon.png') }}" class="w-6 h-6">`,
                                            backgroundSize: 'contain',
                                            className: 'marker bg-transparent',
                                            iconAnchor: [15, 15],
                                            popupAnchor: [0, -15]
                                        })
                                    });
                                    marker.bindPopup(`
                                    <table class="table-auto">
                                        <tr>
                                            <td>Kabupaten/Kota</td>
                                            <td>:</td>
                                            <td>${sample.regency}</td>
                                        </tr>
                                        <tr>
                                            <td>Kecamatan</td>
                                            <td>:</td>
                                            <td>${sample.district}</td>
                                        </tr>
                                        <tr>
                                            <td>Latitude</td>
                                            <td>:</td>
                                            <td>${sample.latitude}</td>
                                        </tr>
                                        <tr>
                                            <td>Longitude</td>
                                            <td>:</td>
                                            <td>${sample.longitude}</td>
                                        </tr>
                                        <tr>
                                            <td>Jumlah Sampel</td>
                                            <td>:</td>
                                            <td>${sample.count}</td>
                                        </tr>
                                        <tr>
                                            <td>Tipe</td>
                                            <td>:</td>
                                            <td>
                                                <table class="table-auto">
                                                    ${
                                                        sample.type.map(type => {
                                                            return `
                                                                                <tr>
                                                                                    <td>${type.name}</td>
                                                                                    <td>:</td>
                                                                                    <td>${type.amount}</td>
                                                                                </tr>
                                                                            `;
                                                        }).join('')
                                                    }
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                `);
                                    markers.addLayer(marker);
                                    // on click
                                    marker.on('click', function(e) {
                                        map.setView(e.latlng, 12);
                                        map.panTo(e.latlng);
                                    });
                                });

                                map.addLayer(markers);
                            } else {
                                map.removeLayer(markers);
                            }
                        }
                    });
                });

                // filter range
                $('#btnFilter').click(function(e) {
                    e.preventDefault();
                    let start_date = $('input[name="startDate"]').val();
                    let end_date = $('input[name="endDate"]').val();

                    $.ajax({
                        url: "{{ route('admin.variable-agent.show.filter-date-range', ':id') }}"
                            .replace(
                                ':id', "{{ $id }}"),
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            start_date: start_date,
                            end_date: end_date
                        },
                        success: function(response) {
                            $('#variableAgentTable').DataTable().clear().destroy();
                            $('#variableAgentTable').DataTable({
                                processing: true,
                                responsive: true,
                                autoWidth: false,
                                data: response.data,
                                columns: [{
                                        data: 'district',
                                        name: 'district'
                                    },
                                    {
                                        data: 'location',
                                        name: 'location'
                                    },
                                    {
                                        data: 'count',
                                        name: 'count'
                                    },
                                    {
                                        data: 'type',
                                        name: 'type'
                                    },
                                    {
                                        data: 'created_at',
                                        name: 'created_at'
                                    }
                                ]
                            });

                            if (response.data.length > 0) {
                                map.removeLayer(markers);
                                markers = L.markerClusterGroup();
                                samples = Object.values(response.samples);

                                samples.map((sample) => {
                                    let marker = L.marker([sample.latitude, sample
                                        .longitude
                                    ], {
                                        icon: L.divIcon({
                                            // using image
                                            html: `<img src="{{ asset('assets/images/vector/mosquito-icon.png') }}" class="w-6 h-6">`,
                                            backgroundSize: 'contain',
                                            className: 'marker bg-transparent',
                                            iconAnchor: [15, 15],
                                            popupAnchor: [0, -15]
                                        })
                                    });
                                    marker.bindPopup(`
                                    <table class="table-auto">
                                        <tr>
                                            <td>Kabupaten/Kota</td>
                                            <td>:</td>
                                            <td>${sample.regency}</td>
                                        </tr>
                                        <tr>
                                            <td>Kecamatan</td>
                                            <td>:</td>
                                            <td>${sample.district}</td>
                                        </tr>
                                        <tr>
                                            <td>Latitude</td>
                                            <td>:</td>
                                            <td>${sample.latitude}</td>
                                        </tr>
                                        <tr>
                                            <td>Longitude</td>
                                            <td>:</td>
                                            <td>${sample.longitude}</td>
                                        </tr>
                                        <tr>
                                            <td>Jumlah Sampel</td>
                                            <td>:</td>
                                            <td>${sample.count}</td>
                                        </tr>
                                        <tr>
                                            <td>Tipe</td>
                                            <td>:</td>
                                            <td>
                                                <table class="table-auto">
                                                    ${
                                                        sample.type.map(type => {
                                                            return `
                                                                                <tr>
                                                                                    <td>${type.name}</td>
                                                                                    <td>:</td>
                                                                                    <td>${type.amount}</td>
                                                                                </tr>
                                                                            `;
                                                        }).join('')
                                                    }
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                `);
                                    markers.addLayer(marker);
                                    // on click
                                    marker.on('click', function(e) {
                                        map.setView(e.latlng, 12);
                                        map.panTo(e.latlng);
                                    });
                                });

                                map.addLayer(markers);
                            } else {
                                map.removeLayer(markers);
                            }
                        }
                    })
                });
            });
        </script>
    @endpush
</x-app-layout>
