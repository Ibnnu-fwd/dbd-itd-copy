let province;
let regency;
let district;
let village;

let latitude;
let longitude;

navigator.geolocation.getCurrentPosition(function (position) {
    $("#latitude").val(position.coords.latitude);
    $("#longitude").val(position.coords.longitude);

    latitude = position.coords.latitude;
    longitude = position.coords.longitude;
});

// Map
$(function () {
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

    $("#province_id").on("change", function () {
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
            success: function (data) {
                let regencies = Object.values(data);
                regencies.forEach((regency) => {
                    $("#regency_id").append(
                        `<option value="${regency.id}">${regency.name}</option>`
                    );
                });
            },
        });
    });

    $("#regency_id").on("change", function () {
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
            success: function (data) {
                let districts = Object.values(data);
                districts.forEach((district) => {
                    $("#district_id").append(
                        `<option value="${district.id}">${district.name}</option>`
                    );
                });
            },
        });
    });

    $("#district_id").on("change", function () {
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
            success: function (data) {
                let villages = Object.values(data);
                villages.forEach((village) => {
                    $("#village_id").append(
                        `<option value="${village.id}">${village.name}</option>`
                    );
                });
            },
        });
    });

    $("#village_id").on("change", function () {
        village = $(this).val();
        $.ajax({
            url: "{{ route('admin.village.show', ':id') }}".replace(
                ":id",
                village
            ),
            type: "GET",
            success: function (data) {
                $("#address").text(data.address);
                $("#latitude").val(data.latitude);
                $("#longitude").val(data.longitude);

                latitude = data.latitude;
                longitude = data.longitude;

                map.setView([latitude, longitude], 13);
                marker = L.marker([latitude, longitude]).addTo(map);
            },
        });
    });
});
