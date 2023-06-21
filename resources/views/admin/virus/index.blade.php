<x-app-layout>
    <x-breadcrumb name="virus" />
    <x-card-container>
        <div class="text-end mb-4">
            <x-link-button route="{{ route('admin.virus.create') }}" color="gray" type="button">
                Tambah
            </x-link-button>
        </div>
        <table id="virusTable" class="w-full">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nama</th>
                    <th>Aksi</th>
                    <th>Deskripsi</th>
                    <th>Gambar</th>
                </tr>
            </thead>
        </table>
    </x-card-container>

    @push('js-internal')
        <script>
            function btnDelete(id, name) {
                let url = "{{ route('admin.virus.destroy', ':id') }}";
                url = url.replace(':id', id);

                Swal.fire({
                    title: 'Apakah anda yakin?',
                    text: `Apakah anda yakin ingin menghapus virus ${name}?`,
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
                                        $('#virusTable').DataTable().ajax.reload();
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
                $('#virusTable').DataTable({
                    processing: true,
                    serverSide: true,
                    responsive: true,
                    autoWidth: false,
                    ajax: '{{ route('admin.virus.index') }}',
                    columns: [{
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex',
                            orderable: false,
                            searchable: false,
                            className: 'text-center'
                        },
                        {
                            data: 'name',
                            name: 'name'
                        },
                        {
                            data: 'action',
                            name: 'action',
                            orderable: false,
                            searchable: false,
                            className: 'text-center'
                        }, {
                            data: 'description',
                            name: 'description',
                            className: 'none'
                        },
                        {
                            data: 'image',
                            name: 'image',
                            orderable: false,
                            searchable: false,
                            className: 'none'
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
