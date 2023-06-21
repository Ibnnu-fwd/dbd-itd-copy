<x-app-layout>
    <x-breadcrumb name="ksh" />
    <x-card-container>
        <div class="flex flex-col gap-3 md:flex-row md:justify-end mb-4">
            <x-link-button route="{{ route('admin.ksh.create') }}" color="gray" type="button"
                class="justify-center">
                Tambah
            </x-link-button>
        </div>
        <table id="kshTable">
            <thead>
                <tr>
                    <th rowspan="2">Kode Sampel</th>
                    <th rowspan="2">Kabupaten/Kota</th>
                    <th rowspan="2">Kecamatan</th>
                    <th rowspan="2">Desa</th>
                    <th colspan="2">Lokasi</th>
                    <th rowspan="2">Jumlah Sampel</th>
                    <th rowspan="2">Oleh</th>
                    <th rowspan="2">Aksi</th>
                </tr>
                <tr>
                    <th>Latitude</th>
                    <th>Longitude</th>
                </tr>
            </thead>
        </table>
    </x-card-container>
    @push('js-internal')
        <script>
            $(function () {
                $('#kshTable').DataTable({
                    processing: true,
                    serverSide: true,
                    responsive: true,
                    autoWidth: false,
                    ajax: '{{ route('admin.ksh.index') }}',
                    columns: [{
                            data: 'sample_code',
                            name: 'sample_code'
                        },
                        {
                            data: 'regency',
                            name: 'regency'
                        },
                        {
                            data: 'district',
                            name: 'district'
                        },
                        {
                            data: 'village',
                            name: 'village'
                        },
                        {
                            data: 'latitude',
                            name: 'latitude'
                        },
                        {
                            data: 'longitude',
                            name: 'longitude'
                        },
                        {
                            data: 'total_sample',
                            name: 'total_sample'
                        },
                        {
                            data: 'created_by',
                            name: 'created_by'
                        },
                        {
                            data: 'action',
                            name: 'action',
                            orderable: false,
                            searchable: false
                        },
                    ],
                });

                @if (Session::has('success'))
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: '{{ Session::get('success') }}',
                    })
                @endif

                @if (Session::has('error'))
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: '{{ Session::get('error') }}',
                    })
                @endif
            });
        </script>
    @endpush
</x-app-layout>
