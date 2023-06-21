<x-app-layout>
    <x-breadcrumb name="ksh.create" />
    <x-card-container class="xl:w-2/3">
        <form action="{{ route('admin.ksh.store') }}" method="POST">
            @csrf
            <div class="sm:grid grid-cols-2 gap-x-4">
                <div>
                    <p class="text-xs 2xl:text-sm font-semibold mb-6">Detail Sampling</p>
                    <x-select id="regency_id" label="Kabupaten/Kota" name="regency_id" isFit="true" required>
                        @foreach ($regencies as $regency)
                            <option value="{{ $regency->id }}">{{ $regency->name }}</option>
                        @endforeach
                    </x-select>
                    <x-select id="district_id" label="Kecamatan" name="district_id" isFit="true" required />
                    <x-select id="village_id" label="Desa" name="village_id" isFit="true" required />
                    <p class="text-xs 2xl:text-sm" id="address"></p>
                </div>
                <div>
                    <p class="text-xs 2xl:text-sm font-semibold mb-6">Detail Koordinat</p>
                    <div class="sm:grid grid-cols-2 gap-x-4">
                        <x-input id="latitude" label="Latitude" name="latitude" type="text" required />
                        <x-input id="longitude" label="Longitude" name="longitude" type="text" required />
                    </div>
                    <x-link-button color="gray" id="btnReloadCoordinate" class="w-full mb-3 justify-center">
                        <i class="fas fa-sync-alt mr-2"></i>
                        <span>Reload Koordinat</span>
                    </x-link-button>
                    <div id="map" class="h-72 mt-4" style="border-radius: 10px; border: none; z-index: 0;">
                    </div>
                </div>
            </div>

            <div class="text-end mt-6">
                <x-button class="bg-primary w-full md:w-auto justify-center">
                    Tambah KSH
                </x-button>
            </div>
        </form>
    </x-card-container>

    @push('js-internal')
        <script>
            let regency;
            let district;
            let village;

            let latitude;
            let longitude;

            $('#btnReloadCoordinate').trigger('click');

            if ($('#latitude').val() == '' && $('#longitude').val() == '') {
                navigator.geolocation.getCurrentPosition(function(position) {
                    $("#latitude").val(position.coords.latitude);
                    $("#longitude").val(position.coords.longitude);

                    latitude = position.coords.latitude;
                    longitude = position.coords.longitude;
                });
            } else {
                latitude = $('#latitude').val();
                longitude = $('#longitude').val();
            }

            function removeDetail(id) {
                let count = $('#detailContainer').children().length;
                if (count > 1) {
                    $(`#detail-${id}`).remove();
                }
            }

            $(function() {
                $("#regency_id").on("change", function() {
                    regency = $(this).val();
                    $("#district_id").empty();
                    $("#village_id").empty();
                    $("#district_id").append(
                        `<option value="" selected disabled>Pilih Kecamatan</option>`
                    );
                    $("#village_id").append(
                        `<option value="" selected disabled>Pilih Desa</option>`
                    );
                    $.ajax({
                        url: "{{ route('admin.district.list') }}",
                        type: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            regency_id: regency,
                        },
                        success: function(data) {
                            let districts = Object.values(data);
                            districts.forEach((district) => {
                                $("#district_id").append(
                                    `<option value="${district.id}">${district.name}</option>`
                                );
                            });
                        },
                    });
                });

                $("#district_id").on("change", function() {
                    district = $(this).val();
                    $("#village_id").empty();
                    $("#village_id").append(
                        `<option value="" selected disabled>Pilih Desa</option>`
                    );
                    $.ajax({
                        url: "{{ route('admin.village.list') }}",
                        type: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            district_id: district,
                        },
                        success: function(data) {
                            let villages = Object.values(data);
                            villages.forEach((village) => {
                                $("#village_id").append(
                                    `<option value="${village.id}">${village.name}</option>`
                                );
                            });
                        },
                    });
                });

                $("#village_id").on("change", function() {
                    village = $(this).val();
                    $.ajax({
                        url: "{{ route('admin.village.show', ':id') }}".replace(
                            ":id",
                            village
                        ),
                        type: "GET",
                        success: function(data) {
                            $("#address").text('Alamat: ' + data.address);
                        },
                    });
                });

                let map = L.map("map").setView([latitude, longitude], 13);

                let googleMaps = L.tileLayer(
                    "https://{s}.google.com/vt/lyrs=m&x={x}&y={y}&z={z}", {
                        maxZoom: 20,
                        subdomains: ["mt0", "mt1", "mt2", "mt3"],
                    }
                ).addTo(map);

                let marker = L.marker([latitude, longitude]).addTo(map);

                map.on("click", onMapClick);

                function onMapClick(e) {
                    $("#latitude").val(e.latlng.lat);
                    $("#longitude").val(e.latlng.lng);

                    latitude = e.latlng.lat;
                    longitude = e.latlng.lng;

                    marker
                        .setLatLng(e.latlng)
                        .addTo(map)
                        // pop up set in above marker
                        .bindPopup(
                            `<table class="table-auto">
                                        <tr>
                                            <td class="px-2 py-1">Latitude</td>
                                            <td class="px-2 py-1">${e.latlng.lat}</td>
                                        </tr>
                                        <tr>
                                            <td class="px-2 py-1">Longitude</td>
                                            <td class="px-2 py-1">${e.latlng.lng}</td>
                                        </tr>`
                        )
                        .openPopup();
                }

                $('#btnReloadCoordinate').on('click', function() {
                    // get lat and lng from navigator
                    navigator.geolocation.getCurrentPosition(function(position) {
                        $("#latitude").val(position.coords.latitude);
                        $("#longitude").val(position.coords.longitude);

                        latitude = position.coords.latitude;
                        longitude = position.coords.longitude;

                        // remove marker
                        map.removeLayer(marker);

                        // add marker
                        marker = L.marker([latitude, longitude]).addTo(map);

                        // set view
                        map.setView([latitude, longitude], 13);
                        map.panTo(new L.LatLng(latitude, longitude));

                        // set popup
                        marker
                            .setLatLng([latitude, longitude])
                            .addTo(map)
                            // pop up set in above marker
                            .bindPopup(
                                `<table class="table-auto">
                                <tr>
                                    <td class="px-2 py-1">Latitude</td>
                                    <td class="px-2 py-1">${latitude}</td>
                                </tr>
                                <tr>
                                    <td class="px-2 py-1">Longitude</td>
                                    <td class="px-2 py-1">${longitude}</td>
                                </tr>
                            </table>`
                            )
                            .openPopup();
                    });
                })

                $('#latitude').on('keyup', function() {
                    let lat = $(this).val();
                    let lng = $('#longitude').val();
                    if (lat !== '' && lng !== '') {
                        // remove marker
                        map.removeLayer(marker);

                        // add marker
                        marker = L.marker([lat, lng]).addTo(map);

                        // set view
                        map.setView([lat, lng], 13);
                        map.panTo(new L.LatLng(lat, lng));

                        // set popup
                        marker
                            .setLatLng([lat, lng])
                            .addTo(map)
                            // pop up set in above marker
                            .bindPopup(
                                `<table class="table-auto">
                                <tr>
                                    <td class="px-2 py-1">Latitude</td>
                                    <td class="px-2 py-1">${lat}</td>
                                </tr>
                                <tr>
                                    <td class="px-2 py-1">Longitude</td>
                                    <td class="px-2 py-1">${lng}</td>
                                </tr>
                            </table>`
                            )
                            .openPopup();
                    }
                });

                $('#longitude').on('keyup', function() {
                    let lat = $('#latitude').val();
                    let lng = $(this).val();
                    if (lat !== '' && lng !== '') {
                        // remove marker
                        map.removeLayer(marker);

                        // add marker
                        marker = L.marker([lat, lng]).addTo(map);

                        // set view
                        map.setView([lat, lng], 13);
                        map.panTo(new L.LatLng(lat, lng));

                        // set popup
                        marker
                            .setLatLng([lat, lng])
                            .addTo(map)
                            // pop up set in above marker
                            .bindPopup(
                                `<table class="table-auto">
                                <tr>
                                    <td class="px-2 py-1">Latitude</td>
                                    <td class="px-2 py-1">${lat}</td>
                                </tr>
                                <tr>
                                    <td class="px-2 py-1">Longitude</td>
                                    <td class="px-2 py-1">${lng}</td>
                                </tr>
                            </table>`
                            )
                            .openPopup();
                    }
                });

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
                        title: 'Gagal',
                        text: '{{ Session::get('error') }}',
                    })
                @endif
            });
        </script>
    @endpush
</x-app-layout>
