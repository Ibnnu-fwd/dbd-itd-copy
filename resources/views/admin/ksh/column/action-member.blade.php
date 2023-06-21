<x-select onchange="changeStatus('{{ $data->id }}')" id="status-{{ $data->id }}">
    <option disabled>Pilih Status</option>
    <option value="1" {{ $data->is_active == 1 ? 'selected' : '' }}>Aktif</option>
    <option value="0" {{ $data->is_active == 0 ? 'selected' : '' }}>Tidak Aktif</option>
</x-select>
