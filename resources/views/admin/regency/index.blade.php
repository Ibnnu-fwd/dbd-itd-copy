<x-app-layout>
    <x-breadcrumb name="regency" />
    <x-card-container>
        <div class="text-end mb-4">
            <x-link-button route="{{ route('admin.regency.create') }}" color="gray" type="button">
                Tambah
            </x-link-button>
        </div>
        <table id="regencyTable" class="w-full">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Kabupaten</th>
                    <th>Provinsi</th>
                    <th>Aksi</th>
                </tr>
            </thead>
        </table>
    </x-card-container>

    @push('js-internal')
        <script>
            function btnDelete(id, name) {
                let url = "{{ route('admin.regency.destroy', ':id') }}";
                url = url.replace(':id', id);

                Swal.fire({
                    title: 'Apakah anda yakin?',
                    text: `Provinsi ${name} akan dihapus`,
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
                                        $('#regencyTable').DataTable().ajax.reload();
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
                $('#regencyTable').DataTable({
                    processing: true,
                    serverSide: true,
                    responsive: true,
                    autoWidth: false,
                    scrollY: 200,
                    scroller: {
                        loadingIndicator: true
                    },
                    ajax: function(data, callback, settings) {
                        // reload from server side
                        $.ajax({
                            url: '{{ route('admin.regency.index') }}',
                            type: 'GET',
                            dataType: 'json',
                            data: data,
                            success: function(response) {
                                callback(response);
                            }
                        });
                    },
                    columns: [{
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex'
                        },
                        {
                            data: 'regency',
                            name: 'regency'
                        },
                        {
                            data: 'province',
                            name: 'province'
                        },
                        {
                            data: 'action',
                            name: 'action',
                            orderable: false,
                            searchable: false
                        },
                    ]
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
