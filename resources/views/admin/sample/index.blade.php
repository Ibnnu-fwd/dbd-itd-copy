<x-app-layout>
    <x-breadcrumb name="sample" />
    @isset($failures)
        <div id="alert-2" class="text-xs 2xl:text-sm flex p-4 mb-4 text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400"
            role="alert">
            <span class="sr-only">Info</span>
            <div>
                <span class="font-medium">
                    Periksa kembali file yang diupload, terdapat beberapa data yang tidak sesuai dengan format yang
                </span>
                <ul class="mt-1.5 ml-4 list-disc list-inside">
                    @foreach ($failures as $failure)
                        <li>
                            <span class="text-red-600 dark:text-red-400">Baris {{ $failure->row() }}</span> -
                            {{ $failure->errors()[0] }}
                        </li>
                    @endforeach
                </ul>
            </div>
            <button type="button"
                class="ml-auto -mx-1.5 -my-1.5 bg-red-50 text-red-500 rounded-lg focus:ring-2 focus:ring-red-400 p-1.5 hover:bg-red-200 inline-flex h-8 w-8 dark:bg-gray-800 dark:text-red-400 dark:hover:bg-gray-700"
                data-dismiss-target="#alert-2" aria-label="Close">
                <span class="sr-only">Close</span>
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd"
                        d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                        clip-rule="evenodd"></path>
                </svg>
            </button>
        </div>
    @endisset
    <x-card-container>
        <div class="text-end mb-4">
            <div class="flex flex-col gap-3 md:flex-row md:justify-end mb-4">
                <x-link-button route="{{ route('admin.sample.create') }}" color="gray" class="justify-center"
                    type="button">
                    Tambah
                </x-link-button>
                <x-link-button route="#" color="gray" type="button" id="btnImport" class="justify-center">
                    Import
                </x-link-button>
            </div>
        </div>
        <table id="sampleTable" class="w-full">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Kode sampel</th>
                    {{-- <th>Metode</th> --}}
                    <th>Kecamatan</th>
                    <th>Alamat</th>
                    <th>Jumlah Sampel</th>
                    <th>
                        <i class="fas fa-user-plus"></i>
                    <th>
                        <i class="fas fa-user-edit"></i>
                    </th>
                    <th>Aksi</th>
                </tr>
            </thead>
        </table>
    </x-card-container>

    <form action="{{ route('admin.sample.import') }}" method="POST" hidden id="formImport"
        enctype="multipart/form-data">
        @csrf
        <input type="file" name="import_file" id="import_file">
        <button type="submit" id="btnSubmit"></button>
    </form>

    @push('js-internal')
        <script>
            function btnDelete(id, name) {
                let url = "{{ route('admin.sample.destroy', ':id') }}";
                url = url.replace(':id', id);

                Swal.fire({
                    title: 'Apakah anda yakin?',
                    text: `Apakah anda yakin ingin menghapus sampel ${name}?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Tidak',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: url,
                            method: 'DELETE',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                if (response.status) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Berhasil',
                                        text: response.message
                                    }).then(() => {
                                        $('#sampleTable').DataTable().ajax.reload();
                                    })
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Gagal',
                                        text: response.message
                                    })
                                }
                            }
                        });
                    }
                });
            }

            $(function() {
                $('#sampleTable').DataTable({
                    processing: true,
                    serverSide: true,
                    responsive: true,
                    autoWidth: false,
                    ajax: "{{ route('admin.sample.index') }}",
                    columns: [{
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex',
                            orderable: false,
                            searchable: false,
                            className: 'text-center'
                        },
                        {
                            data: 'sample_code',
                            name: 'sample_code'
                        },
                        // {
                        //     data: 'sample_method',
                        //     name: 'sample_method'
                        // },
                        {
                            data: 'location',
                            name: 'location'
                        },
                        {
                            data: 'address',
                            name: 'address'
                        },
                        {
                            data: 'sample_count',
                            name: 'sample_count',
                        },
                        {
                            data: 'created_by',
                            name: 'created_by'
                        },
                        {
                            data: 'updated_by',
                            name: 'updated_by'
                        },
                        {
                            data: 'action',
                            name: 'action',
                            orderable: false,
                            searchable: false,
                        },
                    ]
                });

                $('#btnImport').click(function() {
                    $('#import_file').click();

                    $('#import_file').change(function() {
                        Swal.fire({
                            title: 'Konfirmasi',
                            text: 'Apakah anda yakin ingin mengimpor data ini?',
                            icon: 'question',
                            showCancelButton: true,
                            confirmButtonText: 'Ya',
                            cancelButtonText: 'Tidak',
                        }).then((result) => {
                            if (result.isConfirmed) {
                                $('#btnSubmit').click();
                            }
                        });
                    });
                });
            });

            $('#formImport').on('submit', function() {
                Swal.fire({
                    title: 'Mohon tunggu',
                    text: 'Sedang memproses data',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading()
                    },
                });
            });

            @if (Session::has('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: '{{ Session::get('success') }}'
                })
            @endif

            @if (Session::has('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: '{{ Session::get('error') }}'
                })
            @endif
        </script>
    @endpush
</x-app-layout>
