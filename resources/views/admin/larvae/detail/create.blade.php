<x-app-layout>
    <x-breadcrumb name="larvae.detail.create" :data="$larva" />
    <x-card-container>
        <div class="xl:flex justify-between items-center mt-8">
            <p class="text-xs 2xl:text-sm font-semibold mb-6 mt-8">Detail Pemeriksaan</p>
            <x-button id="btnAddLarva" color="gray" class="w-full md:w-auto justify-center">
                <span class="mr-2">Tambah Detail </span> (<span id="countDetailLarva">1</span>)
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
                    <x-select id="aquatic_plant" label="Jenis Tanaman Air" name="aquatic_plant" isFit="true" required>
                        <option value="available">Ada</option>
                        <option value="not_available">Tidak Ada</option>
                    </x-select>
                </div>
                <div class="text-end">
                    <x-button id="removeDetailLarva" type="button" class="bg-red-600 w-full md:w-auto justify-center"
                        onclick="removeDetailLarva('detailLarva-1')">
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
                    $('#countDetailLarva').text(count);
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: 'Minimal 1 detail pemeriksaan',
                    });
                }
            }

            $(function() {
                $('#btnAddLarva').click(function(e) {
                    e.preventDefault();
                    let count = $('#detailLarvaContainer').children().length;
                    let id = count + 1;
                    $('#countDetailLarva').text(id);

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

                $('#btnSubmit').click(function(e) {
                    e.preventDefault();

                    let detailLarva = [];
                    let count = $('#detailLarvaContainer').children().length;
                    for (let i = 1; i <= count; i++) {
                        let tpa_type_id = $(`#detailLarva-${i} #tpa_type_id`).val();
                        let amount_larva = $(`#detailLarva-${i} #amount_larva`).val();
                        let amount_egg = $(`#detailLarva-${i} #amount_egg`).val();
                        let number_of_adults = $(`#detailLarva-${i} #number_of_adults`).val();
                        let water_temperature = $(`#detailLarva-${i} #water_temperature`).val();
                        let salinity = $(`#detailLarva-${i} #salinity`).val();
                        let ph = $(`#detailLarva-${i} #ph`).val();
                        let aquatic_plant = $(`#detailLarva-${i} #aquatic_plant`).val();

                        detailLarva.push({
                            tpa_type_id: tpa_type_id,
                            amount_larva: amount_larva,
                            amount_egg: amount_egg,
                            number_of_adults: number_of_adults,
                            water_temperature: water_temperature,
                            salinity: salinity,
                            ph: ph,
                            aquatic_plant: aquatic_plant,
                        });
                    }

                    $.ajax({
                        url: "{{ route('admin.larvae.detail.store-new', ':id') }}".replace(':id',
                        {{ $larva->id }}),
                        type: "POST",
                        data: {
                            _token: '{{ csrf_token() }}',
                            detailLarva: detailLarva
                        },
                        success: function(response) {
                            if (response.status == "success") {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil',
                                    text: response.message,
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        window.location.href =
                                            "{{ route('admin.larvae.show', ':id') }}"
                                            .replace(':id', {{ $larva->id }});
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
