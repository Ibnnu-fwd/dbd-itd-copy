<x-app-layout>
    <x-breadcrumb name="variable-agent" />
    <x-card-container>
        <table id="variableAgentTable" class="w-full">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Kabupaten/Kota</th>
                    <th>Jumlah Sampel</th>
                    <th>Tipe</th>
                    <th>Aksi</th>
                </tr>
            </thead>
        </table>
    </x-card-container>

    @push('js-internal')
        <script>
            $(function () {
                $('#variableAgentTable').DataTable({
                    processing: true,
                    serverSide: true,
                    responsive: true,
                    autoWidth: false,
                    ajax: "{{ route('admin.variable-agent.index') }}",
                    columns: [{
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex'
                        },
                        {
                            data: 'regency',
                            name: 'regency'
                        },
                        // {
                        //     data: 'location',
                        //     name: 'location'
                        // },
                        {
                            data: 'count',
                            name: 'count'
                        },
                        {
                            data: 'type',
                            name: 'type'
                        },
                        {
                            data: 'action',
                            name: 'action'
                        }
                    ]
                });
            });
        </script>
    @endpush
</x-app-layout>
