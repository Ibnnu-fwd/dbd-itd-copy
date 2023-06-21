<x-app-layout>
    <x-breadcrumb name="larvae.detail.edit" :data="$larva" />
    <x-card-container>
        <div class="text-end mb-8">
            <x-button id="btnAddLarva" color="gray" class="w-full md:w-auto justify-center">
                <span class="mr-2">Tambah Detail</span> (<span
                    id="countDetailLarva">{{ $larva->detailLarvaes->count() }}</span>)
            </x-button>
        </div>
        <div id="detailLarvaContainer">
            @foreach ($larva->detailLarvaes as $detailLarva)
                <div id="detailLarva-{{ $detailLarva->id }}">
                    <div class="mt-4 xl:grid grid-cols-4 gap-x-4">
                        <input type="hidden" name="id" value="{{ $detailLarva->id }}">
                        <x-select id="tpa_type_id" label="Jenis TPA" name="tpa_type_id" isFit="true" required>
                            @foreach ($tpaTypes as $tpaType)
                                <option value="{{ $tpaType->id }}" @if ($detailLarva->tpa_type_id == $tpaType->id) selected @endif>
                                    {{ $tpaType->name }}</option>
                            @endforeach
                        </x-select>
                        <x-input id="amount_larva" label="Jumlah Larva" name="amount_larva" type="number" required
                            :value="$detailLarva->amount_larva" />
                        <x-input id="amount_egg" label="Jumlah Telur" name="amount_egg" type="number" required
                            :value="$detailLarva->amount_egg" />
                        <x-input id="number_of_adults" label="Jumlah Nyamuk Dewasa" name="number_of_adults"
                            type="number" required :value="$detailLarva->number_of_adults" />
                        <x-input id="water_temperature" label="Suhu Air" name="water_temperature" type="number"
                            required :value="$detailLarva->water_temperature" />
                        <x-input id="salinity" label="Salinitas" name="salinity" type="number" required
                            :value="$detailLarva->salinity" />
                        <x-input id="ph" label="pH" name="ph" type="number" required
                            :value="$detailLarva->ph" />
                        <x-select id="aquatic_plant" label="Jenis Tanaman Air" name="aquatic_plant" isFit="true"
                            required>
                            <option value="available"
                                {{ $detailLarva->aquatic_plant == 'available' ? 'selected' : '' }}>
                                Ada</option>
                            <option value="not_available"
                                {{ $detailLarva->aquatic_plant == 'not_available' ? 'selected' : '' }}>
                                Tidak Ada</option>
                        </x-select>
                    </div>
                    <div class="text-end">
                        <x-button id="removeDetailLarva" type="button" class="bg-red-600 w-full md:w-auto justify-center"
                            onclick="removeDetailLarva('detailLarva-{{ $detailLarva->id }}')">
                            Hapus
                        </x-button>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="text-end mt-6">
            <x-button id="btnSubmit" class="bg-primary w-full md:w-auto justify-center">
                Simpan Perubahan
            </x-button>
        </div>
    </x-card-container>

    @push('js-internal')
        <script>
            function removeDetailLarva(id) {
                id = id.split('-')[1];
                // check if there's new class in the element
                let count = $('#detailLarvaContainer').children().length;
                if (count !== 1) {
                    if ($(`#detailLarva-${id}`).hasClass('new')) {
                        $(`#detailLarva-${id}`).remove();
                        count--;
                        $('#countDetailLarva').text(count);
                        return;
                    } else {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Perhatian',
                            text: 'Apakah anda yakin ingin menghapus detail pemeriksaan ini?',
                            showCancelButton: true,
                            confirmButtonText: 'Ya',
                            cancelButtonText: 'Tidak',
                        }).then((result) => {
                            if (result.isConfirmed) {
                                $.ajax({
                                    url: "{{ route('admin.larvae.detail.delete', ':id') }}".replace(':id', id),
                                    type: "POST",
                                    data: {
                                        _token: '{{ csrf_token() }}'
                                    },
                                    success: function(response) {
                                        $(`#detailLarva-${id}`).remove();
                                        count--;
                                        $('#countDetailLarva').text(count);

                                        if (response.status == "success") {
                                            Swal.fire({
                                                icon: 'success',
                                                title: 'Berhasil',
                                                text: response.message,
                                            });
                                            location.reload();
                                        } else {
                                            Swal.fire({
                                                icon: 'error',
                                                title: 'Gagal',
                                                text: response.message,
                                            });
                                        }
                                    }
                                })
                            }
                        });
                    }
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
                            <div id="detailLarva-${id}" class="new">
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
                    let data = [];
                    detailLarva = $('#detailLarvaContainer').children().map(function() {
                        return this.id.split('-')[1];
                    }).get();
                    let count = $('#detailLarvaContainer').children().length;
                    detailLarva.forEach(function(id, index) {
                        let detailLarvaId = $(`#detailLarva-${id} input[name="id"]`).val() ?? null;
                        let tpa_type_id = $(`#detailLarva-${id} #tpa_type_id`).val();
                        let amount_larva = $(`#detailLarva-${id} #amount_larva`).val();
                        let amount_egg = $(`#detailLarva-${id} #amount_egg`).val();
                        let number_of_adults = $(`#detailLarva-${id} #number_of_adults`).val();
                        let water_temperature = $(`#detailLarva-${id} #water_temperature`).val();
                        let salinity = $(`#detailLarva-${id} #salinity`).val();
                        let ph = $(`#detailLarva-${id} #ph`).val();
                        let aquatic_plant = $(`#detailLarva-${id} #aquatic_plant`).val();

                        data.push({
                            id: detailLarvaId ? detailLarvaId : null,
                            tpa_type_id: tpa_type_id,
                            amount_larva: amount_larva,
                            amount_egg: amount_egg,
                            number_of_adults: number_of_adults,
                            water_temperature: water_temperature,
                            salinity: salinity,
                            ph: ph,
                            aquatic_plant: aquatic_plant,
                        });
                    });


                    console.log(data);

                    $.ajax({
                        url: "{{ route('admin.larvae.detail.store', ':id') }}".replace(':id',
                            {{ $larva->id }}),
                        type: "POST",
                        data: {
                            _token: '{{ csrf_token() }}',
                            detailLarva: data
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
