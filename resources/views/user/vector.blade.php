<x-user-layout>

    <main class="pt-8 pb-16 lg:pt-16 lg:pb-24 bg-white dark:bg-gray-900">
        <div class="flex justify-between px-4 mx-auto max-w-screen-xl ">
            <article class="mx-auto w-full max-w-3xl format format-sm sm:format-base lg:format-lg">
                <div class="text-xs 2xl:text-sm">
                    <div class="text-xs 2xl:text-sm">
                        <div class="xl:grid grid-cols-3 items-center">
                            <h2 class="col-span-2">
                                Visualizations of Vector Data
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
                            We have collected samples of vector presence, and we have found that the most common vector
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
                        <div class="mb-8">
                            <div class="xl:grid grid-cols-3 items-center">
                                <h3 class="text-xs 2xl:text-sm col-span-2">
                                    Sample of Year: <span class="font-bold" id="labelYear">{{ date('Y') }}</span>
                                </h3>
                                <x-select name="samplePerYearFilter" id="samplePerYearFilter" label="Tahun">
                                    @foreach (range(2002, date('Y')) as $year)
                                        <option value="{{ $year }}"
                                            @if ($year == date('Y')) selected @endif>
                                            {{ $year }}</option>
                                    @endforeach
                                </x-select>
                            </div>
                            <div style="height: 220px" id="samplePerYearContainer">
                                <canvas id="samplePerYear"></canvas>
                            </div>
                            <p class="text-center text-xs 2xl:text-sm italic">
                                <span class="text-error">*</span>
                                This chart shows the number of samples collected per month in the year selected
                            </p>
                        </div>
                        <div>
                            <div class="xl:grid grid-cols-3 gap-x-4 items-center">
                                <h3 class="text-xs 2xl:text-sm col-span-2">
                                    Sample Per District: <span class="font-bold"
                                        id="labelYearDistrict">{{ date('Y') }}</span>
                                </h3>
                                <div class="grid grid-cols-2 gap-x-4">
                                    <x-select name="samplePerYearDistrictFilter" id="samplePerYearDistrictFilter"
                                        label="Tahun">
                                        @foreach (range(2002, date('Y')) as $year)
                                            <option value="{{ $year }}"
                                                @if ($year == date('Y')) selected @endif>
                                                {{ $year }}</option>
                                        @endforeach
                                    </x-select>
                                    <x-select name="samplePerYearFilterRegency" id="samplePerYearFilterRegency"
                                        label="Kabupaten/Kota">
                                        <option disabled selected value="">Pilih</option>
                                        @foreach ($regencies as $regency)
                                            <option value="{{ $regency->id }}">{{ $regency->name }}</option>
                                        @endforeach
                                    </x-select>
                                </div>
                            </div>
                            <div style="height: 220px" id="samplePerYearDistrictContainer">
                                <canvas id="samplePerDistrict"></canvas>
                            </div>
                            <p class="text-center text-xs 2xl:text-sm italic">
                                <span class="text-error">*</span>
                                This chart shows the number of samples collected per district in the year selected
                            </p>
                        </div>
                    </div>
                    <div class="xl:flex items-start justify-between gap-x-16 mt-10">
                        <div>
                            <h2 class="bg-clip-text bg-gradient-to-r to-purple-500 from-purple-700 text-transparent">
                                Vector Information</h2>
                                <p class="leading-7">
                                    Vectors, as defined by the California Department of Public Health, are “any insect
                                    or other
                                    arthropod, rodent or other animal of public health significance capable of harboring
                                    or
                                    transmitting the causative agents of human disease, or capable of causing human
                                    discomfort and
                                    injury." Under this definition of a vector, the Orange County Mosquito and Vector
                                    Control
                                    District (District) provides surveillance and control measures for rats, mosquitoes,
                                    flies, and
                                    Red Imported Fire Ants.
                                </p>
                        </div>
                        <img src="{{ asset('assets/images/vector/header.jpg') }}" alt=""
                            class="hidden xl:block w-32 h-32 object-cover rounded-xl">
                    </div>
                    <h3>Biology — Mosquito Life Cycle</h3>
                    <p class="leading-6">Mosquitoes have four different stages in their life cycle- egg, larva, pupa,
                        and adult. During
                        each stage of their life cycle the mosquito looks distinctly different than any other life
                        stage.</p>
                    <section class="space-x-3 flex text-xs 2xl:text-sm">
                        <div class="flex flex-col items-center">
                            <img class="w-20 h-20 rounded object-cover order-1"
                                src="{{ asset('assets/images/vector/egg.jpg') }}" alt="Large avatar">
                            <span class="text-center text-xs 2xl:text-sm order-2">
                                Egg
                            </span>
                        </div>
                        <div class="flex flex-col items-center">
                            <img class="w-20 h-20 rounded object-cover order-1"
                                src="{{ asset('assets/images/vector/larva.jpg') }}" alt="Large avatar">
                            <span class="text-center text-xs 2xl:text-sm order-2">
                                Larva
                            </span>
                        </div>
                        <div class="flex flex-col items-center">
                            <img class="w-20 h-20 rounded object-cover order-1"
                                src="{{ asset('assets/images/vector/pupa.jpg') }}" alt="Large avatar">
                            <span class="text-center text-xs 2xl:text-sm order-2">
                                Pupa
                            </span>
                        </div>
                        <div class="flex flex-col items-center">
                            <img class="w-20 h-20 rounded object-cover order-1"
                                src="{{ asset('assets/images/vector/mosquito.jpg') }}" alt="Large avatar">
                            <span class="text-center text-xs 2xl:text-sm order-2">
                                Adult Mosquito
                            </span>
                        </div>
                    </section>
                </div>
            </article>
        </div>
    </main>

    @push('js-internal')
        <!-- Map -->
        <script>
            let samples = Object.values(@json($samples));
            console.log(samples);
            // set last lat long of sample
            let lastSample = samples[samples.length - 1];
            let map = L.map('map').setView([lastSample.latitude, lastSample.longitude], 8);

            L.tileLayer(
                'https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token={accessToken}', {
                    maxZoom: 18,
                    id: 'mapbox/light-v11',
                    tileSize: 512,
                    zoomOffset: -1,
                    accessToken: 'pk.eyJ1IjoiaWJudTIyMDQyMiIsImEiOiJjbGltd3BkdnowMGpsM3JveGVteG52NWptIn0.Ficg1JfyGMJHRgnU48gDdg',
                }
            ).addTo(map);

            map.attributionControl.setPrefix(false);
            map.zoomControl.remove();

            let markers = L.markerClusterGroup();

            samples.forEach(function(sample) {
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
                marker.bindPopup(
                    `
                        <table class="border-collapse border-none">
                            <tbody>
                                <tr>
                                    <th colspan="3" class="p-0">Detail Lokasi</th>
                                </tr>
                                <tr>
                                    <td class="p-0">Provinsi</td>
                                    <td class="p-0">:</td>
                                    <td class="p-0">${sample.province}</td>
                                </tr>
                                <tr>
                                    <td class="p-0">Kabupaten</td>
                                    <td class="p-0">:</td>
                                    <td class="p-0">${sample.regency}</td>
                                </tr>
                                <tr>
                                    <td class="p-0">Kecamatan</td>
                                    <td class="p-0">:</td>
                                    <td class="p-0">${sample.district}</td>
                                </tr>
                                <tr>
                                    <td>Lokasi</td>
                                    <td>:</td>
                                    <td>${sample.location_name}</td>
                                </tr>
                                <tr>
                                    <td>Rumah Sakit</td>
                                    <td>:</td>
                                    <td>${sample.public_health_name}</td>
                                </tr>
                            </tbody>
                        </table>

                        <table class="border-collapse border-none">
                            <tbody>
                                <tr>
                                    <th colspan="2" class="p-0">Detail Sampling</th>
                                </tr>
                                <tr>
                                    <td class="p-0">Jenis Virus</td>
                                    <td class="p-0">Jumlah</td>
                                </tr>
                                ` +
                    sample.type.map(function(type) {
                        return `
                                        <tr>
                                            <td class="p-0">${type.name}</td>
                                            <td class="p-0">${type.amount}</td>
                                        </tr>
                                    `;
                    }).join('') +
                    `
                            </tbody>
                        </table>
                    `
                );
                markers.addLayer(marker);
            });

            // add fullscreen button
            map.addControl(new L.Control.Fullscreen());

            map.addLayer(markers);

            $(function() {
                $('#filterMapYear').change(function(e) {
                    e.preventDefault();
                    // $('#filterMapRegency').val($('#filterMapRegency option:first').val()).change();
                    let year = $(this).val();
                    $.ajax({
                        type: "POST",
                        url: "{{ route('user.vector.filter-map-year') }}",
                        data: {
                            _token: "{{ csrf_token() }}",
                            year: year
                        },
                        success: function(response) {
                            console.log(response.samples);
                            if (response.samples.length == 0) {
                                map.removeLayer(markers);
                                return;
                            }

                            // change marker position
                            markers.clearLayers();
                            let samples = Object.values(response.samples);
                            let lastSample = samples[samples.length - 1];
                            map.setView([lastSample.latitude, lastSample.longitude], 8);

                            samples.forEach(function(sample) {
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
                                marker.bindPopup(
                                    `
                                    <table class="border-collapse border-none">
                                        <tbody>
                                            <tr>
                                                <th colspan="3" class="p-0">Detail Lokasi</th>
                                            </tr>
                                            <tr>
                                                <td class="p-0">Provinsi</td>
                                                <td class="p-0">:</td>
                                                <td class="p-0">${sample.province}</td>
                                            </tr>
                                            <tr>
                                                <td class="p-0">Kabupaten</td>
                                                <td class="p-0">:</td>
                                                <td class="p-0">${sample.regency}</td>
                                            </tr>
                                            <tr>
                                                <td class="p-0">Kecamatan</td>
                                                <td class="p-0">:</td>
                                                <td class="p-0">${sample.district}</td>
                                            </tr>
                                            <tr>
                                                <td>Lokasi</td>
                                                <td>:</td>
                                                <td>${sample.location_name}</td>
                                            </tr>
                                            <tr>
                                                <td>Rumah Sakit</td>
                                                <td>:</td>
                                                <td>${sample.public_health_name}</td>
                                            </tr>
                                        </tbody>
                                    </table>

                                    <table class="border-collapse border-none">
                                        <tbody>
                                            <tr>
                                                <th colspan="2" class="p-0">Detail Sampling</th>
                                            </tr>
                                            <tr>
                                                <td class="p-0">Jenis Virus</td>
                                                <td class="p-0">Jumlah</td>
                                            </tr>
                                            ` +
                                    sample.type.map(function(type) {
                                        return `
                                                    <tr>
                                                        <td class="p-0">${type.name}</td>
                                                        <td class="p-0">${type.amount}</td>
                                                    </tr>
                                                `;
                                    }).join('') +
                                    `
                                        </tbody>
                                    </table>

                                    `
                                );
                                markers.addLayer(marker);
                            });

                            // Update marker position
                            let newLastSample = samples[samples.length - 1];
                            let newLatLng = L.latLng(newLastSample.latitude, newLastSample
                                .longitude);

                            map.addLayer(markers);
                        }
                    });
                });

                $('#filterMapRegency').change(function(e) {
                    e.preventDefault();
                    // $('#filterMapYear').val($('#filterMapYear option:first').val()).change();
                    let regency = $(this).find(':selected').text();
                    let regency_id = $(this).val();
                    $.ajax({
                        type: "POST",
                        url: "{{ route('user.vector.filter-map-regency') }}",
                        data: {
                            _token: "{{ csrf_token() }}",
                            regency_id: regency_id
                        },
                        success: function(response) {
                            if (response.samples.length == 0) {
                                map.removeLayer(markers);
                                return;
                            }

                            // change marker position
                            markers.clearLayers();
                            let samples = Object.values(response.samples);
                            let lastSample = samples[samples.length - 1];
                            map.setView([lastSample.latitude, lastSample.longitude], 8);

                            samples.forEach(function(sample) {
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
                                marker.bindPopup(
                                    `
                                    <table class="border-collapse border-none">
                                        <tbody>
                                            <tr>
                                                <th colspan="3" class="p-0">Detail Lokasi</th>
                                            </tr>
                                            <tr>
                                                <td class="p-0">Provinsi</td>
                                                <td class="p-0">:</td>
                                                <td class="p-0">${sample.province}</td>
                                            </tr>
                                            <tr>
                                                <td class="p-0">Kabupaten</td>
                                                <td class="p-0">:</td>
                                                <td class="p-0">${sample.regency}</td>
                                            </tr>
                                            <tr>
                                                <td class="p-0">Kecamatan</td>
                                                <td class="p-0">:</td>
                                                <td class="p-0">${sample.district}</td>
                                            </tr>
                                            <tr>
                                                <td>Lokasi</td>
                                                <td>:</td>
                                                <td>${sample.location_name}</td>
                                            </tr>
                                            <tr>
                                                <td>Rumah Sakit</td>
                                                <td>:</td>
                                                <td>${sample.public_health_name}</td>
                                            </tr>
                                        </tbody>
                                    </table>

                                    <table class="border-collapse border-none">
                                        <tbody>
                                            <tr>
                                                <th colspan="2" class="p-0">Detail Sampling</th>
                                            </tr>
                                            <tr>
                                                <td class="p-0">Jenis Virus</td>
                                                <td class="p-0">Jumlah</td>
                                            </tr>
                                            ` +
                                    sample.type.map(function(type) {
                                        return `
                                                    <tr>
                                                        <td class="p-0">${type.name}</td>
                                                        <td class="p-0">${type.amount}</td>
                                                    </tr>
                                                `;
                                    }).join('') +
                                    `
                                        </tbody>
                                    </table>

                                    `
                                );
                                markers.addLayer(marker);
                            });

                            // Update marker position
                            let newLastSample = samples[samples.length - 1];
                            let newLatLng = L.latLng(newLastSample.latitude, newLastSample
                                .longitude);

                            map.addLayer(markers);
                        }
                    });
                })
            });
        </script>

        <!-- Yearly Sample -->
        <script>
            let samplePerYear = @json($samplePerYear);

            // Mengambil bulan dan jumlah dari setiap entri data
            let labels = samplePerYear.map((entry) => entry.month);
            let counts = samplePerYear.map((entry) => entry.count);

            // Mengambil jenis nyamuk dari setiap entri samplePerYear
            let mosquitoTypes = samplePerYear[0].type.map((entry) => entry.name);

            // Mengambil jumlah nyamuk dari setiap entri samplePerYear
            let mosquitoAmounts = samplePerYear.map((entry) =>
                entry.type.map((type) => type.amount)
            );

            // Membuat chart dengan Chart.js
            let ctx = document.getElementById("samplePerYear").getContext("2d");
            // width 100%
            ctx.canvas.width = "100%";
            let purplePalette = ["#B799FF", "#ACBCFF", "#AEE2FF"];

            let myChart = new Chart(ctx, {
                type: "line",
                data: {
                    labels: labels,
                    datasets: mosquitoTypes.map((type, index) => ({
                        label: type,
                        data: mosquitoAmounts.map((amounts) => amounts[index]),
                        borderWidth: 2,
                        tension: 0.4,
                        // fill: true,
                        stack: "stack",
                        borderColor: purplePalette[index % purplePalette
                            .length], // Set border color
                        backgroundColor: purplePalette[index % purplePalette
                            .length], // Set fill color
                    })),
                },
                options: {
                    responsive: true,
                    interaction: {
                        mode: "index",
                        intersect: false,
                    },
                    scales: {
                        y: {
                            // stack the bar
                            stacked: true,
                            grid: {
                                display: false,
                            },
                            ticks: {
                                beginAtZero: true,
                                precision: 0,
                                stepSize: 1,
                            },
                        },
                        x: {
                            // stack the bar
                            stacked: true,
                            grid: {
                                display: false,
                            },
                            ticks: {
                                beginAtZero: true,
                                precision: 0,
                                stepSize: 1,
                            },
                        },
                    },
                    plugins: {
                        tooltip: {
                            mode: "index",
                            intersect: false,
                        },
                        legend: {
                            labels: {
                                usePointStyle: true,
                                boxWidth: 5,
                                boxHeight: 5,
                            },
                        },
                    },
                },
            });

            $(function() {
                // Update chart when year is changed
                $('#samplePerYearFilter').change(function(e) {
                    e.preventDefault();
                    let year = $(this).val();
                    $.ajax({
                        url: "{{ route('user.vector.filter-year') }}",
                        type: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            year: year
                        },
                        success: function(response) {
                            let samplePerYear = response.samplePerYear;
                            myChart.destroy();
                            $('#samplePerYear').remove();
                            $('#samplePerYearContainer').attr('style', 'height: 220px');
                            $('#samplePerYearContainer').html(
                                '<canvas id="samplePerYear"></canvas>');
                            if (samplePerYear.length == 0) {
                                // remove style
                                $('#samplePerYearContainer').removeAttr('style');
                                $('#samplePerYearContainer').html(
                                    '<p class="text-center">No data available</p>');
                                $('#labelYear').html(year);
                                return;
                            }
                            samplePerYear = samplePerYear
                            labels = samplePerYear.map((entry) => entry.month);
                            counts = samplePerYear.map((entry) => entry.count);
                            mosquitoTypes = samplePerYear[0].type.map((entry) => entry.name);
                            mosquitoAmounts = samplePerYear.map((entry) =>
                                entry.type.map((type) => type.amount)
                            );
                            ctx = document.getElementById("samplePerYear").getContext("2d");
                            ctx.canvas.width = "100%";
                            myChart = new Chart(ctx, {
                                type: "line",
                                data: {
                                    labels: labels,
                                    datasets: mosquitoTypes.map((type, index) => ({
                                        label: type,
                                        data: mosquitoAmounts.map((amounts) =>
                                            amounts[index]),
                                        borderWidth: 2,
                                        tension: 0.4,
                                        // fill: true,
                                        stack: "stack",
                                        borderColor: purplePalette[index %
                                            purplePalette
                                            .length], // Set border color
                                        backgroundColor: purplePalette[index %
                                            purplePalette
                                            .length], // Set fill color
                                    })),
                                },
                                options: {
                                    responsive: true,
                                    interaction: {
                                        mode: "index",
                                        intersect: false,
                                    },
                                    scales: {
                                        y: {
                                            // stack the bar
                                            stacked: true,
                                            grid: {
                                                display: false,
                                            },
                                            ticks: {
                                                beginAtZero: true,
                                                precision: 0,
                                                stepSize: 1,
                                            },
                                        },
                                        x: {
                                            // stack the bar
                                            stacked: true,
                                            grid: {
                                                display: false,
                                            },
                                            ticks: {
                                                beginAtZero: true,
                                                precision: 0,
                                                stepSize: 1,
                                            },
                                        },
                                    },
                                    plugins: {
                                        tooltip: {
                                            mode: "index",
                                            intersect: false,
                                        },
                                        legend: {
                                            labels: {
                                                usePointStyle: true,
                                                boxWidth: 5,
                                                boxHeight: 5,
                                            },
                                        },
                                    },
                                },
                            });
                            $('#labelYear').html(year);
                        }
                    });
                });
            });
        </script>

        <!-- Sample Per District -->
        <script>
            let data = Object.values(@json($samplePerDistrict));
            // Prepare data for the chart
            let sampleDistrictLabel = [];
            let datasetsDistrict = [];
            let virusTypes = {};

            data.forEach(function(item) {
                sampleDistrictLabel.push(item.district);
                let districtData = {};

                item.type.forEach(function(type) {
                    districtData[type.name] = type.amount;
                    if (!virusTypes[type.name]) {
                        virusTypes[type.name] = [];
                    }
                });

                for (let key in virusTypes) {
                    if (virusTypes.hasOwnProperty(key)) {
                        if (districtData.hasOwnProperty(key)) {
                            virusTypes[key].push(districtData[key]);
                        } else {
                            virusTypes[key].push(0);
                        }
                    }
                }
            });

            // let purplePalette = ["#B799FF", "#ACBCFF", "#AEE2FF"];

            for (let key in virusTypes) {
                if (virusTypes.hasOwnProperty(key)) {
                    datasetsDistrict.push({
                        label: key,
                        data: virusTypes[key],
                        backgroundColor: purplePalette[datasetsDistrict.length % purplePalette.length],
                        borderColor: purplePalette[datasetsDistrict.length % purplePalette.length],
                    });
                }
            }

            // Create the chart
            let sampleChartCtx = document.getElementById('samplePerDistrict').getContext('2d');
            sampleChartCtx.canvas.width = '100%';
            let sampleChart = new Chart(sampleChartCtx, {
                type: 'bar',
                data: {
                    labels: sampleDistrictLabel,
                    datasets: datasetsDistrict
                },
                options: {
                    scales: {
                        x: {
                            stacked: true
                        },
                        y: {
                            stacked: true,
                            beginAtZero: true
                        }
                    },
                    plugins: {
                        tooltip: {
                            mode: 'index',
                            intersect: false
                        },
                        legend: {
                            labels: {
                                usePointStyle: true,
                                boxWidth: 5,
                                boxHeight: 5,
                            },
                        },
                    }
                }
            });

            $(function() {
                $('#samplePerYearDistrictFilter').change(function(e) {
                    e.preventDefault();
                    // select first option
                    $('#samplePerYearFilter').val($('#samplePerYearFilter option:first').val()).change();
                    let year = $(this).val();
                    $.ajax({
                        type: "POST",
                        url: "{{ route('user.vector.filter-year-district') }}",
                        data: {
                            _token: "{{ csrf_token() }}",
                            year: year
                        },
                        success: function(response) {
                            let data = response.samplePerDistrict;
                            sampleChart.destroy();
                            $('#samplePerDistrict').remove();
                            $('#samplePerYearDistrictContainer').attr('style', 'height: 220px');
                            $('#samplePerYearDistrictContainer').html(
                                '<canvas id="samplePerDistrict"></canvas>');
                            if (data.length == 0) {
                                // remove style
                                $('#samplePerYearDistrictContainer').removeAttr('style');
                                $('#samplePerYearDistrictContainer').html(
                                    '<p class="text-center">No data available</p>');
                                $('#labelYearDistrict').html(year);
                                return;
                            }
                            data = Object.values(data);
                            sampleDistrictLabel = [];
                            datasetsDistrict = [];
                            virusTypes = {};

                            data.forEach(function(item) {
                                sampleDistrictLabel.push(item.district);
                                let districtData = {};

                                item.type.forEach(function(type) {
                                    districtData[type.name] = type.amount;
                                    if (!virusTypes[type.name]) {
                                        virusTypes[type.name] = [];
                                    }
                                });

                                for (let key in virusTypes) {
                                    if (virusTypes.hasOwnProperty(key)) {
                                        if (districtData.hasOwnProperty(key)) {
                                            virusTypes[key].push(districtData[key]);
                                        } else {
                                            virusTypes[key].push(0);
                                        }
                                    }
                                }
                            });

                            for (let key in virusTypes) {
                                if (virusTypes.hasOwnProperty(key)) {
                                    datasetsDistrict.push({
                                        label: key,
                                        data: virusTypes[key],
                                        backgroundColor: purplePalette[datasetsDistrict
                                            .length %
                                            purplePalette.length],
                                        borderColor: purplePalette[datasetsDistrict.length %
                                            purplePalette.length],
                                    });
                                }
                            }

                            sampleChartCtx = document.getElementById('samplePerDistrict')
                                .getContext('2d');
                            sampleChartCtx.canvas.width = '100%';
                            sampleChart = new Chart(sampleChartCtx, {
                                type: 'bar',
                                data: {
                                    labels: sampleDistrictLabel,
                                    datasets: datasetsDistrict
                                },
                                options: {
                                    scales: {
                                        x: {
                                            stacked: true
                                        },
                                        y: {
                                            stacked: true,
                                            beginAtZero: true
                                        }
                                    },
                                    plugins: {
                                        tooltip: {
                                            mode: 'index',
                                            intersect: false
                                        },
                                        legend: {
                                            labels: {
                                                usePointStyle: true,
                                                boxWidth: 5,
                                                boxHeight: 5,
                                            },
                                        },
                                    }
                                }
                            });

                            $('#labelYearDistrict').html(year);
                        }
                    });
                });

                $('#samplePerYearFilterRegency').change(function(e) {
                    // select first option
                    $('#samplePerYearDistrictFilter').val($('#samplePerYearDistrictFilter option:first').val())
                        .change();
                    e.preventDefault();
                    let regency = $(this).find(':selected').text();
                    let regency_id = $(this).val();
                    $.ajax({
                        type: "POST",
                        url: "{{ route('user.vector.filter-regency') }}",
                        data: {
                            _token: "{{ csrf_token() }}",
                            regency_id: regency_id
                        },
                        success: function(response) {
                            let data = Object.values(response.samples);
                            sampleChart.destroy();
                            $('#samplePerDistrict').remove();
                            $('#samplePerYearDistrictContainer').attr('style', 'height: 220px');
                            $('#samplePerYearDistrictContainer').html(
                                '<canvas id="samplePerDistrict"></canvas>');
                            if (data.length == 0) {
                                // remove style
                                $('#samplePerYearDistrictContainer').removeAttr('style');
                                $('#samplePerYearDistrictContainer').html(
                                    '<p class="text-center">No data available</p>');
                                $('#labelYearDistrict').html(regency);
                                return;
                            }
                            data = Object.values(data);
                            sampleDistrictLabel = [];
                            datasetsDistrict = [];
                            virusTypes = {};

                            data.forEach(function(item) {
                                sampleDistrictLabel.push(item.district);
                                let districtData = {};

                                item.type.forEach(function(type) {
                                    districtData[type.name] = type.amount;
                                    if (!virusTypes[type.name]) {
                                        virusTypes[type.name] = [];
                                    }
                                });

                                for (let key in virusTypes) {
                                    if (virusTypes.hasOwnProperty(key)) {
                                        if (districtData.hasOwnProperty(key)) {
                                            virusTypes[key].push(districtData[key]);
                                        } else {
                                            virusTypes[key].push(0);
                                        }
                                    }
                                }
                            });

                            for (let key in virusTypes) {
                                if (virusTypes.hasOwnProperty(key)) {
                                    datasetsDistrict.push({
                                        label: key,
                                        data: virusTypes[key],
                                        backgroundColor: purplePalette[datasetsDistrict
                                            .length %
                                            purplePalette.length],
                                        borderColor: purplePalette[datasetsDistrict.length %
                                            purplePalette.length],
                                    });
                                }
                            }

                            sampleChartCtx = document.getElementById('samplePerDistrict')
                                .getContext('2d');
                            sampleChartCtx.canvas.width = '100%';
                            sampleChart = new Chart(sampleChartCtx, {
                                type: 'bar',
                                data: {
                                    labels: sampleDistrictLabel,
                                    datasets: datasetsDistrict
                                },
                                options: {
                                    scales: {
                                        x: {
                                            stacked: true
                                        },
                                        y: {
                                            stacked: true,
                                            beginAtZero: true
                                        }
                                    },
                                    plugins: {
                                        tooltip: {
                                            mode: 'index',
                                            intersect: false
                                        },
                                        legend: {
                                            labels: {
                                                usePointStyle: true,
                                                boxWidth: 5,
                                                boxHeight: 5,
                                            },
                                        },
                                    }
                                }
                            });

                            $('#labelYearDistrict').html(regency);
                        }
                    });
                })
            });
        </script>
    @endpush
</x-user-layout>
