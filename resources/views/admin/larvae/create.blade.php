<x-app-layout>
    <x-breadcrumb name="larvae.create" />
    <x-card-container>
        <div class="sm:grid grid-cols-3 gap-x-4">
            <div>
                <p class="text-xs 2xl:text-sm font-semibold mb-6">Detail Lokasi</p>
                <x-select id="regency_id" label="Kabupaten/Kota" name="regency_id" isFit="true" required>
                    @foreach ($regencies as $regency)
                        <option value="{{ $regency->id }}">{{ $regency->name }}</option>
                    @endforeach
                </x-select>
                <x-select id="district_id" label="Kecamatan" name="district_id" isFit="true" required />
                <x-select id="village_id" label="Desa" name="village_id" isFit="true" required />
                <x-textarea id="address" label="Alamat" name="address" isFit="true" required />
            </div>
            <div>
                <p class="text-xs 2xl:text-sm font-semibold mb-6">Detail Demografi</p>
                <x-select id="location_type_id" label="Jenis Lokasi" name="location_type_id" isFit="true" required>
                    @foreach ($locationTypes as $locationType)
                        <option value="{{ $locationType->id }}">{{ $locationType->name }}</option>
                    @endforeach
                </x-select>
                <x-select id="settlement_type_id" label="Jenis Pemukiman" name="settlement_type_id" isFit="true" required>
                    @foreach ($settlementTypes as $settlementType)
                        <option value="{{ $settlementType->id }}">{{ $settlementType->name }}</option>
                    @endforeach
                </x-select>
                <x-select id="environment_type_id" label="Jenis Lingkungan" name="environment_type_id" isFit="true" required>
                    @foreach ($environmentTypes as $environmentType)
                        <option value="{{ $environmentType->id }}">{{ $environmentType->name }}</option>
                    @endforeach
                </x-select>
                <x-select id="building_type_id" label="Jenis Bangunan" name="building_type_id" isFit="true" required>
                    @foreach ($buildingTypes as $buildingType)
                        <option value="{{ $buildingType->id }}">{{ $buildingType->name }}</option>
                    @endforeach
                </x-select>
                <x-select id="floor_type_id" label="Jenis Lantai" name="floor_type_id" isFit="true" required>
                    @foreach ($floorTypes as $floorType)
                        <option value="{{ $floorType->id }}">{{ $floorType->name }}</option>
                    @endforeach
                </x-select>
            </div>
            <div>
                <p class="text-xs 2xl:text-sm font-semibold mb-4">Detail Koordinat</p>
                <div class="sm:grid grid-cols-2 gap-x-4">
                    <x-input id="latitude" label="Latitude" name="latitude" type="text" required />
                    <x-input id="longitude" label="Longitude" name="longitude" type="text" required />
                </div>
                <x-link-button color="gray" id="btnReloadCoordinate" class="w-full mb-3 justify-center">
                    <i class="fas fa-sync-alt mr-2"></i>
                    <span>Reload Koordinat</span>
                </x-link-button>
                <div id="map" class="h-72 mt-4" style="border-radius: 10px; border: none; z-index: 0;"></div>
            </div>
        </div>

        <div class="xl:flex justify-between items-center mt-8">
            <p class="text-xs 2xl:text-sm font-semibold mb-6 mt-8">Detail Pemeriksaan</p>
            <x-button id="btnAddLarva" color="gray" class="w-full md:w-auto justify-center">
                <span>Tambah Detail</span>
            </x-button>
        </div>
        <div id="detailLarvaContainer">
            <div id="detailLarva-1">
                <div class="mt-4 xl:grid grid-cols-4 gap-x-4">
                    <x-icon-button id="removeDetailLarva" color="red" class="absolute top-0 right-0"
                        icon="fas fa-times" />
                    <x-select id="tpa_type_id" label="Jenis TPA" name="tpa_type_id" isFit="true" required>
                        @foreach ($tpaTypes as $tpaType)
                            <option value="{{ $tpaType->id }}">{{ $tpaType->name }}</option>
                        @endforeach
                    </x-select>
                    <x-input id="amount_larva" label="Jumlah Larva" name="amount_larva" type="number" required />
                    <x-input id="amount_egg" label="Jumlah Telur" name="amount_egg" type="number" required />
                    <x-input id="number_of_adults" label="Jumlah Nyamuk Dewasa" name="number_of_adults" type="number"
                        required />
                    <x-input id="water_temperature" label="Suhu Air" name="water_temperature" type="number" required />
                    <x-input id="salinity" label="Salinitas" name="salinity" type="number" required />
                    <x-input id="ph" label="pH" name="ph" type="number" required />
                    <x-select id="aquatic_plant" label="Jenis Tanaman Air" name="aquatic_plant" isFit="true"
                        required>
                        <option value="available">Ada</option>
                        <option value="not_available">Tidak Ada</option>
                    </x-select>
                </div>
                <div class="text-end">
                    <x-button id="removeDetailLarva" type="button" class="bg-red-600 w-full md:w-auto justify-center" onclick="removeDetailLarva('detailLarva-1')">
                        <span>Hapus</span>
                    </x-button>
                </div>
            </div>
        </div>

        <div class="text-end mt-6">
            <x-button id="btnSubmit" class="bg-primary w-full md:w-auto justify-center">
                Tambah Larva
            </x-button>
        </div>
    </x-card-container>

    @push('js-internal')
        <script>
            function removeDetailLarva(id) {
                let count = $('#detailLarvaContainer').children().length;
                if (count !== 1) {
                    $(`#${id}`).remove();
                    count--;
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: 'Minimal 1 detail pemeriksaan',
                    });
                }
            }

            $('#btnReloadCoordinate').trigger('click');

            let regency;
            let district;
            let village;

            let latitude;
            let longitude;

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
                            $('#address').val(data.address);
                            $("#address").text(data.address);
                        },
                    });
                });

                $('#btnAddLarva').click(function(e) {
                    e.preventDefault();
                    let count = $('#detailLarvaContainer').children().length;
                    let id = count + 1;

                    $('#detailLarvaContainer').append(`
                            <div id="detailLarva-${id}">
                                <div class="mt-4 xl:grid grid-cols-4 gap-x-4">
                                    <x-icon-button id="removeDetailLarva" color="red" class="absolute top-0 right-0"
                                        icon="fas fa-times" />
                                    <x-select id="tpa_type_id" label="Jenis TPA" name="tpa_type_id" isFit="true" required>
                                        @foreach ($tpaTypes as $tpaType)
                                            <option value="{{ $tpaType->id }}">{{ $tpaType->name }}</option>
                                        @endforeach
                                    </x-select>
                                    <x-input id="amount_larva" label="Jumlah Larva" name="amount_larva" type="number" required />
                                    <x-input id="amount_egg" label="Jumlah Telur" name="amount_egg" type="number" required />
                                    <x-input id="number_of_adults" label="Jumlah Nyamuk Dewasa" name="number_of_adults" type="number"
                                        required />
                                    <x-input id="water_temperature" label="Suhu Air" name="water_temperature" type="number" required />
                                    <x-input id="salinity" label="Salinitas" name="salinity" type="number" required />
                                    <x-input id="ph" label="pH" name="ph" type="number" required />
                                    <x-select id="aquatic_plant" label="Jenis Tanaman Air" name="aquatic_plant" isFit="true"
                                        required>
                                        <option value="available">Ada</option>
                                        <option value="not_available">Tidak Ada</option>
                                    </x-select>
                                </div>
                                <div class="text-end">
                                    <x-button id="removeDetailLarva" type="button" class="bg-red-600 w-full md:w-auto justify-center" onclick="removeDetailLarva('detailLarva-${id}')">
                                        <span>Hapus</span>
                                    </x-button>
                                </div>
                            </div>
                        `);

                    });

                let map = L.map("map").setView([latitude, longitude], 13);

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

                $('#btnSubmit').click(function(e) {
                    e.preventDefault();

                    let detailLarva = [];
                    let count = $('#detailLarvaContainer').children().length;
                    for (let i = 1; i <= count; i++) {
                        let tpa_type_id         = $(`#detailLarva-${i} #tpa_type_id`).val();
                        let amount_larva        = $(`#detailLarva-${i} #amount_larva`).val();
                        let amount_egg          = $(`#detailLarva-${i} #amount_egg`).val();
                        let number_of_adults    = $(`#detailLarva-${i} #number_of_adults`).val();
                        let water_temperature   = $(`#detailLarva-${i} #water_temperature`).val();
                        let salinity            = $(`#detailLarva-${i} #salinity`).val();
                        let ph                  = $(`#detailLarva-${i} #ph`).val();
                        let aquatic_plant       = $(`#detailLarva-${i} #aquatic_plant`).val();

                        detailLarva.push({
                            tpa_type_id         : tpa_type_id,
                            amount_larva        : amount_larva,
                            amount_egg          : amount_egg,
                            number_of_adults    : number_of_adults,
                            water_temperature   : water_temperature,
                            salinity            : salinity,
                            ph                  : ph,
                            aquatic_plant       : aquatic_plant,
                        });
                    }

                    $.ajax({
                        type: "POST",
                        url: "{{route('admin.larvae.store')}}",
                        data: {
                            _token              : "{{ csrf_token() }}",
                            regency_id          : $('#regency_id').val(),
                            district_id         : $('#district_id').val(),
                            village_id          : $('#village_id').val(),
                            address             : $('#address').val(),
                            location_type_id    : $('#location_type_id').val(),
                            settlement_type_id  : $('#settlement_type_id').val(),
                            environment_type_id : $('#environment_type_id').val(),
                            building_type_id    : $('#building_type_id').val(),
                            floor_type_id       : $('#floor_type_id').val(),
                            latitude            : $('#latitude').val(),
                            longitude           : $('#longitude').val(),
                            detailLarva         : detailLarva,
                        },
                        // on processing
                        beforeSend: function() {
                            Swal.fire({
                                title: 'Mohon Tunggu',
                                html: 'Sedang memproses data',
                                didOpen: () => {
                                    Swal.showLoading()
                                },
                            });
                        },
                        success: function (response) {
                            if(response.status == 'success')
                            {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil',
                                    text: response.message,
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        window.location.href = "{{ route('admin.larvae.index') }}";
                                    }
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal',
                                    text: response.message,
                                });
                            }
                        }
                    });
                });
            });
        </script>
    @endpush
</x-app-layout>
