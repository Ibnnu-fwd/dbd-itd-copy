<x-app-layout>
    <x-breadcrumb name="abj" />
    <x-card-container>
        <div id="map" class="z-0 mb-4" style="height: 350px; border-radius: 6px"></div>
        <table id="abjTable">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Kecamatan</th>
                    <th>Jumlah Sampel</th>
                    <th>Jumlah Pemeriksaan</th>
                    <th>ABJ (%)</th>
                    <th>Tanggal</th>
                </tr>
            </thead>
        </table>
    </x-card-container>

    @push('js-internal')
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


        <script>
            $(function() {
                $('#abjTable').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: "{{ route('admin.abj.index') }}",
                    reponsive: true,
                    autoWidth: false,
                    columns: [{
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex'
                        },
                        {
                            data: 'district',
                            name: 'district',
                        },
                        {
                            data: 'total_sample',
                            name: 'total_sample',
                        },
                        {
                            data: 'total_check',
                            name: 'total_check',
                        },
                        {
                            data: 'abj',
                            name: 'abj'
                        },
                        {
                            data: 'created_at',
                            name: 'created_at'
                        },
                    ],
                });
            });
        </script>
    @endpush
</x-app-layout>
