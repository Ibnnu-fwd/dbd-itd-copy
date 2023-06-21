<x-app-layout>
    <x-breadcrumb name="larvae" />
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
        <div id="map" class="z-0 mb-4" style="height: 350px; border-radius: 6px"></div>
        <div class="flex flex-col gap-3 md:flex-row md:justify-end mb-4">
            <x-link-button route="{{ route('admin.larvae.create') }}" color="gray" type="button"
                class="justify-center">
                Tambah
            </x-link-button>
        </div>
        <table id="larvaeTable">
            <thead>
                <tr>
                    <th rowspan="2">#</th>
                    <th rowspan="2">Kode</th>
                    <th rowspan="2">Kecamatan</th>
                    <th colspan="5">Demografi</th>
                    <th rowspan="2">Aksi</th>
                </tr>
                <tr>
                    <th>Lokasi</th>
                    <th>Permukiman</th>
                    <th>Lingkungan</th>
                    <th>Bangunan</th>
                    <th>Lantai</th>
                </tr>
            </thead>
        </table>
    </x-card-container>
    @push('js-internal')
        <script>
            $(function() {
                $('#larvaeTable').DataTable({
                    processing: true,
                    serverSide: true,
                    responsive: true,
                    autoWidth: false,
                    ajax: "{{ route('admin.larvae.index') }}",
                    columns: [{
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex',
                            orderable: false,
                            searchable: false,
                        },
                        {
                            data: 'larva_code',
                            name: 'larva_code',
                        },
                        {
                            data: 'district',
                            name: 'district',
                        },
                        {
                            data: 'location',
                            name: 'location',
                        },
                        {
                            data: 'settlement',
                            name: 'settlement',
                        },
                        {
                            data: 'environment',
                            name: 'environment',
                        },
                        {
                            data: 'building',
                            name: 'building',
                        },
                        {
                            data: 'floor',
                            name: 'floor',
                        },
                        {
                            data: 'action',
                            name: 'action',
                            orderable: false,
                            searchable: false,
                        },
                    ],
                });

                let larvae = @json($larvae);

                // get center map
                let centerCoordinate = [];
                for (let i = 0; i < larvae.length; i++) {
                    centerCoordinate.push([larvae[i].latitude, larvae[i].longitude]);
                }
                let map = L.map('map').setView(centerCoordinate[0], 8);

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

                for (let i = 0; i < larvae.length; i++) {
                    let marker = L.marker([larvae[i].latitude, larvae[i].longitude], {
                        icon: L.divIcon({
                            // image
                            html: `<img src="{{ asset('assets/images/larvae/icon.jpg') }}" class="w-6 h-6">`,
                            className: 'text-white bg-transparent',
                            iconAnchor: [15, 15],
                            popupAnchor: [0, -15]
                        })
                    });

                    marker.bindPopup(
                        `<table class="table-sm">
                            <thead>
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
                            </thead>
                            <tbody>
                                ` +
                        larvae[i].detail_larvaes.map((data, index) => {
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
                        `</tbody>
                        </table>`
                        // adjust width popup
                    ).on('popupopen', function() {
                        $('.leaflet-popup-content').width('auto');
                    });

                    // on click pan to marker
                    marker.on('click', function() {
                        map.setZoom(15);
                        map.panTo(marker.getLatLng());
                    });

                    markers.addLayer(marker);
                }

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
                        url: "{{ route('admin.larvae.filter-month') }}",
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            month: index
                        },
                        success: function(response) {
                            $('#larvaeTable').DataTable().clear().destroy();
                            $('#larvaeTable').DataTable({
                                processing: true,
                                responsive: true,
                                autoWidth: false,
                                data: response.data ?? [],
                                columns: [{
                                        data: 'DT_RowIndex',
                                        name: 'DT_RowIndex',
                                        orderable: false,
                                        searchable: false,
                                    },
                                    {
                                        data: 'larva_code',
                                        name: 'larva_code',
                                    },
                                    {
                                        data: 'district',
                                        name: 'district',
                                    },
                                    {
                                        data: 'location',
                                        name: 'location',
                                    },
                                    {
                                        data: 'settlement',
                                        name: 'settlement',
                                    },
                                    {
                                        data: 'environment',
                                        name: 'environment',
                                    },
                                    {
                                        data: 'building',
                                        name: 'building',
                                    },
                                    {
                                        data: 'floor',
                                        name: 'floor',
                                    },
                                    {
                                        data: 'action',
                                        name: 'action',
                                        orderable: false,
                                        searchable: false,
                                    },
                                ],
                            });

                            // map
                            map.removeLayer(markers);
                            markers = L.markerClusterGroup();
                            for (let i = 0; i < response.larvae.length; i++) {
                                let marker = L.marker([response.larvae[i].latitude, response.larvae[
                                    i].longitude], {
                                    icon: L.divIcon({
                                        html: `<img src="{{ asset('assets/images/larvae/icon.jpg') }}" class="w-6 h-6">`,
                                        className: 'text-white bg-transparent',
                                        // put popup on top of marker
                                        iconAnchor: [15, 15],
                                        popupAnchor: [0, -15]
                                    })
                                });

                                marker.bindPopup(
                                    `<table class="table-sm">
                                        <thead>
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
                                        </thead>
                                        <tbody>
                                            ` +
                                    response.larvae[i].detail_larvaes.map((data, index) => {
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
                                    `</tbody>
                                    </table>`
                                    // adjust width popup
                                ).on('popupopen', function() {
                                    $('.leaflet-popup-content').width('auto');
                                });

                                markers.addLayer(marker);
                            }
                            map.addLayer(markers);
                        }
                    });
                });
                // filter range
                $('#btnFilter').click(function(e) {
                    e.preventDefault();
                    let start_date = $('input[name="startDate"]').val();
                    let end_date = $('input[name="endDate"]').val();

                    $.ajax({
                        url: "{{ route('admin.larvae.filter-date-range') }}",
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            start_date: start_date,
                            end_date: end_date
                        },
                        success: function(response) {
                            $('#larvaeTable').DataTable().clear().destroy();
                            $('#larvaeTable').DataTable({
                                processing: true,
                                responsive: true,
                                autoWidth: false,
                                data: response.data ?? [],
                                columns: [{
                                        data: 'DT_RowIndex',
                                        name: 'DT_RowIndex',
                                        orderable: false,
                                        searchable: false,
                                    },
                                    {
                                        data: 'larva_code',
                                        name: 'larva_code',
                                    },
                                    {
                                        data: 'district',
                                        name: 'district',
                                    },
                                    {
                                        data: 'location',
                                        name: 'location',
                                    },
                                    {
                                        data: 'settlement',
                                        name: 'settlement',
                                    },
                                    {
                                        data: 'environment',
                                        name: 'environment',
                                    },
                                    {
                                        data: 'building',
                                        name: 'building',
                                    },
                                    {
                                        data: 'floor',
                                        name: 'floor',
                                    },
                                    {
                                        data: 'action',
                                        name: 'action',
                                        orderable: false,
                                        searchable: false,
                                    },
                                ],
                            });

                            // map
                            map.removeLayer(markers);
                            markers = L.markerClusterGroup();
                            for (let i = 0; i < response.larvae.length; i++) {
                                let marker = L.marker([response.larvae[i].latitude, response.larvae[
                                    i].longitude], {
                                    icon: L.divIcon({
                                        html: `<img src="{{ asset('assets/images/larvae/icon.jpg') }}" class="w-6 h-6">`,
                                        className: 'text-white bg-transparent',
                                        iconAnchor: [15, 15],
                                        popupAnchor: [0, -15]
                                    })
                                });

                                marker.bindPopup(
                                    `<table class="table-sm">
                                        <thead>
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
                                        </thead>
                                        <tbody>
                                            ` +
                                    response.larvae[i].detail_larvaes.map((data, index) => {
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
                                    `</tbody>
                                    </table>`
                                    // adjust width popup
                                ).on('popupopen', function() {
                                    $('.leaflet-popup-content').width('auto');
                                });
                                markers.addLayer(marker);
                            }
                            map.addLayer(markers);
                        }
                    })
                });
            });
        </script>
    @endpush
</x-app-layout>
