<div class="inline-flex gap-2">
    <x-icon-button route="{{ route('admin.district.edit', $data->id) }}" color="gray" icon="fas fa-edit" />
    <x-icon-button onclick="btnDelete('{{ $data->id }}', '{{ $data->name }}')" color="red" icon="fas fa-trash" />
</div>
