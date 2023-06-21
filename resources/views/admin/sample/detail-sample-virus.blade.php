<x-app-layout>
    <x-breadcrumb name="sample.detail-sample.virus" :data="$sample" />
    <x-card-container>
        <h3 class="mb-4 font-semibold text-gray-900 text-xs 2xl:text-sm">Detail Sampel</h3>
        <ul
            class="items-center w-full text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-lg xl:flex">
            @foreach ($morphotypes as $morphotype)
                <li class="w-full border-b border-gray-200 sm:border-b-0 sm:border-r dark:border-gray-600">
                    <div class="flex items-center pl-3">
                        <input id="{{ $morphotype->id }}-checkbox-list" type="checkbox" value=""
                            class="w-4 h-4 text-primary bg-gray-100 border-gray-300 rounded"
                            onchange="generateForm('{{ $morphotype->id }}')">
                        <label for="{{ $morphotype->id }}-checkbox-list"
                            class="w-full py-3 ml-2 text-xs 2xl:text-sm font-medium text-gray-900 dark:text-gray-300">{{ $morphotype->name }}</label>
                    </div>
                </li>
            @endforeach
        </ul>

        <div id="sampleTable" class="mt-5">
        </div>

        <x-button class="bg-primary hidden" type="submit">
            {{ __('Simpan Data Sampling') }}
        </x-button>
    </x-card-container>

    @push('js-internal')
        <script>
            let serotypes = @json($serotypes);

            function generateForm(id) {
                let check = $('#' + id + '-checkbox-list').is(':checked') ? true : false;
                if (check) {
                    // add row morphotype name, input each denv 1, 2, 3, 4 using serotypes
                    $('#sampleTable').append(`
                        <div id="${id}-row" class="sm:flex gap-x-2 border-b border-gray-200 py-4">
                            <div class="sm:w-1/4">
                                <x-input id="${id}-amount" label="Jumlah Morfotipe ${id}" name="${id}-amount" type="number" required />
                            </div>
                            ` +
                        serotypes.map(serotype => {
                            return `
                                <div class="sm:w-1/4">
                                    <x-input id="${id}-${serotype.id}" label="${serotype.name}" name="${id}-${serotype.id}" type="number" required />
                                </div>
                            `;
                        }).join('') +
                        `
                        </div>
                    `);

                    // keep order morphotype
                    let morphotypes = [];
                    $('#sampleTable').children().each(function() {
                        morphotypes.push($(this).attr('id').split('-')[0]);
                    });

                    // sort morphotype
                    morphotypes.sort();

                    // append morphotype to sampleTable
                    morphotypes.forEach(morphotype => {
                        $('#sampleTable').append($('#' + morphotype + '-row'));
                    });

                } else {
                    $('#' + id + '-row').remove();
                }

                if ($('#sampleTable').children().length > 0) {
                    $('.bg-primary').removeClass('hidden');
                } else {
                    $('.bg-primary').addClass('hidden');
                }
            }

            $('button[type="submit"]').click(function(e) {
                e.preventDefault();
                // get all checked morphotype id and all input value
                let morphotypes = [];
                let morphotypeValues = [];
                $('#sampleTable').children().each(function() {
                    morphotypes.push($(this).attr('id').split('-')[0]);
                });

                morphotypes.forEach(morphotype => {
                    let morphotypeValue = {
                        morphotype_id: morphotype,
                        amount: $('#' + morphotype + '-amount').val(),
                        serotypes: []
                    };

                    serotypes.forEach(serotype => {
                        morphotypeValue.serotypes.push({
                            serotype_id: serotype.id,
                            amount: $('#' + morphotype + '-' + serotype.id).val()
                        });
                    });

                    morphotypeValues.push(morphotypeValue);
                });

                console.log('morphotypes: ', morphotypes);
                console.log('morphotypeValues: ', morphotypeValues);

                // check all input value in morphotypeValues is not empty
                let isNotEmpty = true;
                morphotypeValues.forEach(morphotypeValue => {
                    if (morphotypeValue.amount == '') {
                        isNotEmpty = false;
                    }

                    morphotypeValue.serotypes.forEach(serotype => {
                        if (serotype.amount == '') {
                            isNotEmpty = false;
                        }
                    });
                });

                if (isNotEmpty) {
                    $.ajax({
                        url: "{{ route('admin.sample.detail-sample.virus.store', $sample->id) }}",
                        type: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            morphotypeValues: morphotypeValues,
                        },
                        success: function(response) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: 'Data berhasil disimpan!',
                            }).then((result) => {
                                location.reload();
                            });
                        },
                        error: function(response) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'Terjadi kesalahan!',
                            });
                        }
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Data tidak boleh kosong!',
                    });
                }
            });

            let detail_sample_morphotypes = @json($sample['detailSampleMorphotypes']);
            detail_sample_morphotypes.forEach(detail_sample_morphotype => {
                $('#' + detail_sample_morphotype.morphotype_id + '-checkbox-list').prop('checked', true);
                generateForm(detail_sample_morphotype.morphotype_id);
                $('#' + detail_sample_morphotype.morphotype_id + '-amount').val(detail_sample_morphotype.amount);
                detail_sample_morphotype.detail_sample_serotypes.forEach(detail_sample_serotype => {
                    $('#' + detail_sample_morphotype.morphotype_id + '-' + detail_sample_serotype.serotype_id)
                        .val(detail_sample_serotype.amount);
                });
                // add delete button below morphotype row
                $('#' + detail_sample_morphotype.morphotype_id + '-row').append(`
                    <div class="sm:w-1/4 my-auto">
                        <button id="${detail_sample_morphotype.morphotype_id}-delete" class="w-full px-4 py-2.5 mt-2 text-xs 2xl:text-sm font-medium text-white bg-red-500 rounded-md hover:bg-red-600 focus:outline-none focus-visible:ring-2 focus-visible:ring-white focus-visible:ring-opacity-75" onclick="deleteMorphotype('${detail_sample_morphotype.id}')">
                            Hapus
                        </button>
                    </div>
                `);

                // disable checked morphotype checkbox to uncheck
                $('#' + detail_sample_morphotype.morphotype_id + '-checkbox-list').prop('disabled', true);
            });

            function deleteMorphotype(id) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Peringatan',
                    text: 'Apakah anda yakin ingin menghapus data ini?',
                    showCancelButton: true,
                    confirmButtonText: 'Hapus',
                    cancelButtonText: 'Tidak',
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ route('admin.sample.detail-sample.virus.morphotype.delete') }}",
                            type: "POST",
                            data: {
                                _token: "{{ csrf_token() }}",
                                detailSampleMorphotypeId: id,
                            },
                            success: function(response) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil',
                                    text: 'Data berhasil dihapus!',
                                }).then((result) => {
                                    location.reload();
                                });
                            },
                            error: function(response) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Oops...',
                                    text: 'Terjadi kesalahan!',
                                });
                            }
                        });
                    }
                });
            };
        </script>
    @endpush
</x-app-layout>
