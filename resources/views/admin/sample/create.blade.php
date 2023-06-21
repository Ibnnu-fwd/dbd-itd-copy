<x-app-layout>
    <x-breadcrumb name="sample.create" />
    <x-card-container>
        <form action="{{ route('admin.sample.store') }}" method="POST">
            @csrf
            <div class="sm:grid grid-cols-3 gap-x-4">
                <div>
                    <p class="text-xs 2xl:text-sm font-semibold mb-6">Detail Sampling</p>
                    {{-- <x-select id="sample_method_id" label="Metode Pengambilan Sampel" name="sample_method_id" isFit="true"
                        required>
                        @foreach ($sampleMethods as $sampleMethod)
                            <option value="{{ $sampleMethod->id }}">{{ $sampleMethod->name }}</option>
                        @endforeach
                    </x-select> --}}
                    <x-input id="public_health_name" label="Pukesmas" name="public_health_name" type="text" />
                    <x-input id="location_name" label="Nama Lokasi" name="location_name" type="text" required />
                    <x-select id="location_type_id" label="Jenis Lokasi" name="location_type_id" isFit="true"
                        required>
                        @foreach ($locationTypes as $locationType)
                            <option value="{{ $locationType->id }}">{{ $locationType->name }}</option>
                        @endforeach
                    </x-select>
                    <x-textarea id="description" label="Keterangan" name="description" type="text" />
                </div>
                <div>
                    <p class="text-xs 2xl:text-sm font-semibold mb-6">Detail Lokasi</p>
                    <x-select id="province_id" label="Provinsi" name="province_id" isFit="true" required>
                        @foreach ($provinces as $province)
                            <option value="{{ $province->id }}">{{ $province->name }}</option>
                        @endforeach
                    </x-select>
                    <x-select id="regency_id" label="Kabupaten/Kota" name="regency_id" isFit="true" required />
                    <x-select id="district_id" label="Kecamatan" name="district_id" isFit="true" required />
                    <x-select id="village_id" label="Desa" name="village_id" isFit="true" required />
                    <p id="address" class="text-xs 2xl:text-sm"></p>
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
            <hr class="my-6 border-gray-100">

            <div class="p-4 text-xs 2xl:text-sm text-gray-800 rounded-lg bg-gray-100 mb-3" role="alert">
                Jenis vektor yang anda pilih akan menjadi jenis vektor yang akan diambil sampelnya dan akan diisi
                kemudian
                data morfotipe dan serotipenya
            </div>
            <ul
                class="items-center w-full gap-x-2 text-sm font-medium text-gray-900 bg-white rounded-lg sm:flex flex-wrap">
                @foreach ($viruses as $virus)
                    <li class="">
                        <div class="flex items-center pl-3">
                            <input id="virus-{{ $virus->id }}-checkbox-list" type="checkbox"
                                value="{{ $virus->id }}" name="viruses[]"
                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                            <label for="virus-{{ $virus->id }}-checkbox-list"
                                class="w-full py-3 ml-2 text-xs 2xl:text-sm font-medium text-gray-900 dark:text-gray-300">{{ $virus->name }}</label>
                        </div>
                    </li>
                @endforeach
            </ul>

            @error('viruses')
                <p class="text-xs text-red-500 mt-2">{{ $message }}</p>
            @enderror

            <div class="text-end mt-6">
                <x-button class="bg-primary w-full md:w-auto justify-center">
                    Tambah Sampel
                </x-button>
            </div>
        </form>
    </x-card-container>

    @push('js-internal')
        <!-- Detail Location -->
        <script>
            $('#btnReloadCoordinate').trigger('click');
            let province;
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

            // Map
            $(function() {

                $('form').on('submit', function() {
                    $('#latitude').prop('disabled', false);
                    $('#longitude').prop('disabled', false);

                    $('button[type="submit"]').prop('disabled', true);
                    $('button[type="submit"]').html(
                        `<i class="fas fa-circle-notch fa-spin mr-2"></i> Loading...`
                    );
                });

                let map = L.map("map").setView([latitude, longitude], 13);

                // tile google maps source
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

                $("#province_id").on("change", function() {
                    province = $(this).val();
                    $("#regency_id").empty();
                    $("#district_id").empty();
                    $("#village_id").empty();
                    $("#regency_id").append(
                        `<option value="" selected disabled>Pilih Kabupaten/Kota</option>`
                    );
                    $("#district_id").append(
                        `<option value="" selected disabled>Pilih Kecamatan</option>`
                    );
                    $("#village_id").append(
                        `<option value="" selected disabled>Pilih Desa</option>`
                    );
                    $.ajax({
                        url: "{{ route('admin.regency.list') }}",
                        type: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            province_id: province,
                        },
                        success: function(data) {
                            let regencies = Object.values(data);
                            regencies.forEach((regency) => {
                                $("#regency_id").append(
                                    `<option value="${regency.id}">${regency.name}</option>`
                                );
                            });
                        },
                    });
                });

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
                            $("#address").text(data.address);
                            // $("#latitude").val(data.latitude);
                            // $("#longitude").val(data.longitude);
                        },
                    });
                });

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
                            </tr>`
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
                            </tr>`
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
                            </tr>`
                            )
                            .openPopup();
                    }
                });

                @if (Session::has('success'))
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: '{{ Session::get('success') }}',
                    });
                @endif

                @if (Session::has('error'))
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: '{{ Session::get('error') }}',
                    });
                @endif
            });
        </script>
    @endpush
</x-app-layout>
