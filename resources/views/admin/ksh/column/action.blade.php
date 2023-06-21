<div class="inline-flex gap-2">
    <x-icon-button route="{{ route('admin.ksh.show', $data->id) }}" class="bg-primary px-3" icon="fas fa-clipboard-check" />
    <x-icon-button route="{{ route('admin.ksh.edit', $data->id) }}" color="gray" icon="fas fa-edit" />
    <x-icon-button onclick="btnDelete('{{ $data->id }}')" class="bg-red-600"
        icon="fas fa-trash" />
</div>
