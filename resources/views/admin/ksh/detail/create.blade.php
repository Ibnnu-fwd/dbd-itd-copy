<x-app-layout>
    <x-breadcrumb name="ksh.detail.create" :data="$ksh" />
    <x-card-container class="sm:w-2/3">
        <form action="{{ route('admin.ksh.detail.store', $ksh->id) }}" method="POST">
            @csrf
            <div class="sm:grid sm:grid-cols-2 sm:gap-4">
                <div>
                    <p class="text-xs 2xl:text-sm font-semibold mb-6">Detail Lokasi</p>
                    <x-input id="house_name" name="house_name" label="Nama Rumah" required class="max-w-xs" :value="old('house_name')" />
                    <x-input id="house_owner" name="house_owner" label="Nama Pemilik Rumah" required class="max-w-xs" :value="old('house_owner')" />
                    <x-select id="tpa_type_id" label="Jenis TPA" name="tpa_type_id">
                        @foreach ($tpaTypes as $tpaType)
                            <option value="{{ $tpaType->id }}">{{ $tpaType->name }}</option>
                        @endforeach
                    </x-select>
                    <div>
                        <p class="mb-2 text-xs 2xl:text-sm">Status Larva <span class="text-red-500">*</span></p>
                        <div class="flex items-center">
                            <div class="flex items-center mr-2">
                                <input id="positive-list-radio-license" type="radio" value="1"
                                    name="larva_status"
                                    class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500" />
                                <label for="positive-list-radio-license"
                                    class="w-full py-3 ml-2 text-xs 2xl:text-sm font-medium text-gray-900 dark:text-gray-300">Ada</label>
                            </div>
                            <div class="flex items-center">
                                <input id="negative-list-radio-license" type="radio" value="0"
                                    name="larva_status"
                                    class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500" />
                                <label for="negative-list-radio-license"
                                    class="w-full py-3 ml-2 text-xs 2xl:text-sm font-medium text-gray-900 dark:text-gray-300">
                                    Tidak Ada
                                </label>
                            </div>
                        </div>
                        @error('larva_status')
                            <p class="text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
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
            <div class="text-end mt-6">
                <x-button class="bg-primary w-full md:w-auto justify-center">
                    Tambah Detail KSH
                </x-button>
            </div>
        </form>
    </x-card-container>
    @push('js-internal')
        <script>
            $('#btnReloadCoordinate').trigger('click');

            let latitude;
            let longitude;

            if ($('#latitude').val() == '' && $('#longitude').val() == '') {
                navigator.geolocation.getCurrentPosition(function(position) {
                    $("#latitude").val(position.coords.latitude);
                    $("#longitude").val(position.coords.longitude);

                    latitude = position.coords.latitude;
                    longitude = position.coords.longitude;
                }, function(error) {
                    $('#btnReloadCoordinate').trigger('click');
                });
            } else {
                latitude = $('#latitude').val();
                longitude = $('#longitude').val();
            }

            $(function() {
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
            });
        </script>
    @endpush
</x-app-layout>
