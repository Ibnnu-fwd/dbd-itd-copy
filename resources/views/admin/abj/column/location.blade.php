<table class="w-full table-compact capitalize">
    <thead>
        <tr>
            <th class="capitalize" rowspan="2">Rumah</th>
            <th class="capitalize" rowspan="2">Pemilik</th>
            <th class="capitalize" rowspan="2">Jenis TPA</th>
            <th class="capitalize" colspan="2" align="center">Larva</th>
        </tr>
        <tr>
            <th class="capitalize">Ada</th>
            <th class="capitalize">Tidak</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $item)
            @foreach ($item['coordinate'] as $val)
                <tr>
                    <td>{{ $val['house_name'] }}</td>
                    <td>{{ $val['house_owner'] }}</td>
                    <td>{{ $val['tpaType']['name'] }}</td>
                    <td>{{ $val['larva_status'] == 1 ? '✓' : '' }}</td>
                    <td>{{ $val['larva_status'] == 0 ? '✓' : '' }}</td>
                </tr>
            @endforeach
        @endforeach
    </tbody>
</table>
