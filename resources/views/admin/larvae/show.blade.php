<x-app-layout>
    <x-breadcrumb name="larvae.show" :data="$larva" />
    <x-card-container>
        <div class="flex flex-col gap-3 md:flex-row md:justify-end mb-4">
            <x-link-button route="{{ route('admin.larvae.detail.create', $larva->id) }}" color="gray" class="justify-center" type="button">
                Tambah
            </x-link-button>
            <x-link-button route="{{ route('admin.larvae.detail.edit', $larva->id) }}" color="gray" type="button" class="justify-center">
                Ubah
            </x-link-button>
        </div>
        <table class="w-full" id="larvaeTable">
            <thead>
                <tr>
                    <th>#</th>
                    <th>TPA</th>
                    <th>Larva</th>
                    <th>Telur</th>
                    <th>Nyamuk Dewasa</th>
                    <th>Suhu Air</th>
                    <th>Salinitas</th>
                    <th>PH</th>
                    <th>Tumbuhan Air</th>
                </tr>
            </thead>
        </table>
    </x-card-container>

    @push('js-internal')
        <script>
            $(function() {
                $('#larvaeTable').DataTable({
                    processing: true,
                    serverSide: true,
                    responsive: true,
                    autoWidth: false,
                    ajax: "{{ route('admin.larvae.show', $larva->id) }}",
                    columns: [{
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex'
                        }, {
                            data: 'tpa',
                            name: 'tpa'
                        },
                        {
                            data: 'amount_larva',
                            name: 'amount_larva'
                        },
                        {
                            data: 'amount_egg',
                            name: 'amount_egg'
                        },
                        {
                            data: 'number_of_adults',
                            name: 'number_of_adults'
                        },
                        {
                            data: 'water_temperature',
                            name: 'water_temperature'
                        },
                        {
                            data: 'salinity',
                            name: 'salinity'
                        },
                        {
                            data: 'ph',
                            name: 'ph'
                        },
                        {
                            data: 'aquatic_plant',
                            name: 'aquatic_plant'
                        },
                    ]
                })
            });
        </script>
    @endpush
</x-app-layout>
