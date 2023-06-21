<div class="inline-flex gap-2">
    {{-- <x-icon-button route="{{ route('admin.sample.show', $data->id) }}" color="gray" icon="fas fa-eye" /> --}}
    <x-icon-button route="{{ route('admin.sample.detail-sample', $data->id) }}" class="bg-primary px-3"
        icon="fas fa-clipboard-check" />
    <x-icon-button route="{{ route('admin.sample.edit', $data->id) }}" color="gray" icon="fas fa-edit" />
    <x-icon-button onclick="btnDelete('{{ $data->id }}', '{{ $data->name }}')" color="red" icon="fas fa-trash" />
</div>
