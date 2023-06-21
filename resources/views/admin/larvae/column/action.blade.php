<div class="inline-flex gap-2">
    <x-icon-button route="{{ route('admin.larvae.show', $data->id) }}" class="bg-primary" icon="fas fa-eye" />
    <x-icon-button route="{{ route('admin.larvae.edit', $data->id) }}" color="gray" icon="fas fa-edit" />
    <x-icon-button onclick="btnDelete('{{ $data->id }}', '{{ $data->regency->name }}')" color="red" icon="fas fa-trash" />
</div>
