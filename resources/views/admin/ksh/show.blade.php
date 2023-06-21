<x-app-layout>
    <x-breadcrumb name="ksh.show" :data="$ksh" />
    <x-card-container>
        <div class="flex flex-col gap-3 md:flex-row md:justify-end mb-4">
            <x-link-button route="{{ route('admin.ksh.detail.create', $ksh->id) }}" color="gray" type="button"
                class="justify-center">
                Tambah
            </x-link-button>
        </div>

        <table id="kshTable">
            <thead>
                <tr>
                    <th rowspan="2">#</th>
                    <th rowspan="2">Rumah</th>
                    <th rowspan="2">Pemilik Rumah</th>
                    <th rowspan="2">Jenis Penampungan Air</th>
                    <th colspan="2">Status Larva</th>
                    <th rowspan="2">Aksi</th>
                </tr>
                <tr>
                    <th>Ada</th>
                    <th>Tidak Ada</th>
                </tr>
            </thead>
        </table>
    </x-card-container>

    @push('js-internal')
        <script>
            $(function() {
                $('#kshTable').DataTable({
                    processing: true,
                    serverSide: true,
                    responsive: true,
                    autoWidth: false,
                    ajax: "{{ route('admin.ksh.show', ':id') }}".replace(':id', "{{ $ksh->id }}"),
                    columns: [{
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex',
                        }, {
                            data: 'house_name',
                            name: 'house_name',
                        },
                        {
                            data: 'house_owner',
                            name: 'house_owner',
                        },
                        {
                            data: 'tpa_type',
                            name: 'tpa_type',
                        },
                        {
                            data: 'larva_status_true',
                            name: 'larva_status_true',
                        },
                        {
                            data: 'larva_status_false',
                            name: 'larva_status_false',
                        },
                        {
                            data: 'action',
                            name: 'action',
                            orderable: false,
                            searchable: false,
                        }
                    ],
                });

                @if (Session::has('success'))
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: '{{ Session::get('success') }}',
                        showConfirmButton: false,
                    });
                @endif

                @if (Session::has('error'))
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: '{{ Session::get('error') }}',
                        showConfirmButton: false,
                    });
                @endif
            });
        </script>
    @endpush
</x-app-layout>
