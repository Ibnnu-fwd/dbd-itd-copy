<x-app-layout>
    <x-breadcrumb name="dashboard" />
    <div id="map" class="z-0 mb-4" style="height: 350px; border-radius: 6px; margin-top:30px;"></div>
    <div class="xl:grid grid-cols-2 gap-x-4">
        <x-card-container height="" style="height: 220px">
            <p class="text-xs 2xl:text-sm font-semibold">
                Statistik Sampel
            </p>
            <canvas id="samplePerYear"></canvas>
        </x-card-container>
        <div class="xl:grid grid-cols-2 gap-4 md:flex md:flex-wrap">
            <div class="max-w-sm p-6 bg-white border border-gray-200 rounded-lg shadow flex items-center mb-4 md:mb-0">
                <i class="fas fa-users fa-2x text-primary mr-4 "></i>
                <div>
                    <a href="#">
                        <h5 class="text-2xl font-semibold tracking-tight text-gray-900 dark:text-white">
                            {{ number_format($usersCount, 0, ',', '.') }}
                        </h5>
                    </a>
                    <p class="font-normal text-xs 2xl:text-sm text-gray-500">Pengguna</p>
                </div>
            </div>
            <div class="max-w-sm p-6 bg-white border border-gray-200 rounded-lg shadow flex items-center mb-4 md:mb-0">
                <i class="fas fa-chart-simple fa-2x text-success mr-4"></i>
                <div>
                    <a href="#">
                        <h5 class="text-2xl font-semibold tracking-tight text-gray-900 dark:text-white">
                            {{ number_format($totalSample, 0, ',', '.') }}
                        </h5>
                    </a>
                    <p class="font-normal text-xs 2xl:text-sm text-gray-500">Sampel Nyamuk</p>
                </div>
            </div>
            <div class="max-w-sm p-6 bg-white border border-gray-200 rounded-lg shadow flex items-center mb-4 md:mb-0">
                <i class="fas fa-mosquito fa-2x text-error mr-4"></i>
                <div>
                    <a href="#">
                        <h5 class="text-2xl font-semibold tracking-tight text-gray-900 dark:text-white">
                            {{ number_format($totalMosquito, 0, ',', '.') }}
                        </h5>
                    </a>
                    <p class="font-normal text-xs 2xl:text-sm text-gray-500">Total Nyamuk</p>
                </div>
            </div>
            <div class="max-w-sm p-6 bg-white border border-gray-200 rounded-lg shadow flex items-center mb-4 md:mb-0">
                <i class="fas fa-worm fa-2x text-warning mr-4"></i>
                <div>
                    <a href="#">
                        <h5 class="mb-1 text-2xl font-semibold tracking-tight text-gray-900 dark:text-white">
                            {{ number_format($totalLarva, 0, ',', '.') }}
                        </h5>
                    </a>
                    <p class="font-normal text-xs 2xl:text-sm text-gray-500">Total Larva</p>
                </div>
            </div>
        </div>
    </div>

    @push('js-internal')
        <script src="https://api.mapbox.com/mapbox-gl-js/v2.6.1/mapbox-gl.js"></script>
        <link href="https://api.mapbox.com/mapbox-gl-js/v2.15.0/mapbox-gl.css" rel="stylesheet">
        <script src="https://api.mapbox.com/mapbox-gl-js/v2.15.0/mapbox-gl.js"></script>

        <script>
            function getColor(abj_total) {
                return abj_total > 90 ? '#1cc88a' :
                    abj_total >= 15 && abj_total < 90 ? '#f6c23e' :
                    abj_total <= 15 ? '#e74a3b' :
                    '#858796';
            }

            const map = L.map('map').setView([-8.1624029, 113.717332], 8);

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

            let larvae = Object.values(@json($larvae));
            let sample = Object.values(@json($sample));
            let centerCoordinateSample = [];
            for (let i = 0; i < sample.length; i++) {
                centerCoordinateSample.push([sample[i].latitude, sample[i].longitude]);
            }

            centerCoordinateSample.forEach(coordinate => {
                var el = L.divIcon({
                    className: 'custom-marker',
                    html: '<img src="{{ asset('assets/images/vector/mosquito-icon.png') }}" class="w-6 h-6">'
                });

                L.marker([parseFloat(coordinate[0]), parseFloat(coordinate[1])], {
                    icon: el
                }).addTo(map);
            });

            let centerCoordinate = [];
            for (let i = 0; i < larvae.length; i++) {
                centerCoordinate.push([larvae[i].latitude, larvae[i].longitude]);
            }

            centerCoordinate.forEach(coordinate => {
                var el = L.divIcon({
                    className: 'custom-marker',
                    html: '<img src="{{ asset('assets/images/larvae/icon.jpg') }}" class="w-6 h-6">'
                });

                L.marker([parseFloat(coordinate[0]), parseFloat(coordinate[1])], {
                    icon: el
                }).addTo(map);
            });

            function updateMapData() {
                let abj = Object.values(@json($abj));

                fetch("{{ asset('assets/geojson/indonesia_villages_border.geojson') }}")
                    .then((response) => response.json())
                    .then((data) => {
                        const geojson = {
                            type: 'FeatureCollection',
                            features: []
                        };

                        data.forEach((dataItem) => {
                            abj.forEach((abjItem) => {
                                if (abjItem.district === dataItem.sub_district) {
                                    geojson.features.push({
                                        type: 'Feature',
                                        geometry: {
                                            type: 'Polygon',
                                            coordinates: [dataItem.border]
                                        },
                                        properties: {
                                            color: getColor(abjItem.abj_total),
                                            regency: dataItem.district,
                                            district: dataItem.sub_district,
                                            village: dataItem.name,
                                            abj: abjItem.abj_total,
                                            total_sample: abjItem.total_sample,
                                            total_check: abjItem.total_check
                                        }
                                    });
                                }
                            });
                        });

                        L.geoJSON(geojson, {
                            style: function(feature) {
                                return {
                                    fillColor: feature.properties.color,
                                    color: feature.properties.color,
                                    weight: 0.5,
                                    fillOpacity: 0.5,
                                };
                            },
                            onEachFeature: function(feature, layer) {
                                layer.on('click', function(e) {
                                    const coordinates = e.latlng;
                                    const properties = feature.properties;

                                    const popupContent = `
                                        <p><strong>Kabupaten/Kota:</strong> ${properties.regency}</p>
                                        <p><strong>Kecamatan:</strong> ${properties.district}</p>
                                        <p><strong>ABJ:</strong> ${properties.abj}%</p>
                                        <p><strong>Total Sampel:</strong> ${properties.total_sample}</p>
                                        <p><strong>Total Pemeriksaan:</strong> ${properties.total_check}</p>
                                    `;

                                    L.popup()
                                        .setLatLng(coordinates)
                                        .setContent(popupContent)
                                        .openOn(map);

                                    // Zoom to the clicked feature
                                    map.fitBounds(layer.getBounds(), {
                                        padding: [100, 100]
                                    });
                                });

                                layer.on('mouseover', function(e) {
                                    map.getContainer().style.cursor = 'pointer';
                                });

                                layer.on('mouseout', function(e) {
                                    map.getContainer().style.cursor = '';
                                });
                            }
                        }).addTo(map);
                    });
            }

            updateMapData(); // map update

            // full screen
            L.control.fullscreen().addTo(map);
        </script>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            $(function() {
                let samplePerYear = @json($samplePerYear);

                // Mengambil bulan dan jumlah dari setiap entri data
                var labels = samplePerYear.map(entry => entry.month);
                var counts = samplePerYear.map(entry => entry.count);

                // Mengambil jenis nyamuk dari setiap entri samplePerYear
                var mosquitoTypes = samplePerYear[0].type.map(entry => entry.name);

                // Mengambil jumlah nyamuk dari setiap entri samplePerYear
                var mosquitoAmounts = samplePerYear.map(entry => entry.type.map(type => type.amount));

                // Membuat chart dengan Chart.js
                var ctx = document.getElementById('samplePerYear').getContext('2d');
                // width 100%
                ctx.canvas.width = '100%';
                var myChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: mosquitoTypes.map((type, index) => ({
                            label: type,
                            data: mosquitoAmounts.map(amounts => amounts[index]),
                            borderWidth: 2,
                            tension: 0.4,
                            fill: true,
                            stack: 'stack'
                        }))
                    },
                    options: {
                        responsive: true,
                        interaction: {
                            mode: 'index',
                            intersect: false
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
                        },
                    }
                });
            });
        </script>
    @endpush
</x-app-layout>
