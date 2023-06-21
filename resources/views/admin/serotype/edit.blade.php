<x-app-layout>
    <x-breadcrumb name="serotype.edit" :data="$serotype" />
    <div class="lg:w-1/2 w-full">
        <x-card-container>
            <form action="{{ route('admin.serotype.update', $serotype->id) }}" method="POST">
                @csrf
                @method('PUT')
                <x-input id="name" name="name" type="text" label="Jenis serotipe" class="uppercase" required
                    :value="$serotype->name" />
                <x-button type="submit" class="bg-primary">Simpan</x-button>
            </form>
        </x-card-container>
    </div>

    @push('js-internal')
        <script>
            $(function() {
                $('#virus_id').select2({
                    placeholder: 'Pilih virus',
                    allowClear: true,
                    ajax: {
                        url: '{{ route('admin.virus.list') }}',
                        dataType: 'json',
                        delay: 250,
                        processResults: function(data) {
                            return {
                                results: $.map(data, function(item) {
                                    return {
                                        text: item.name,
                                        id: item.id
                                    }
                                })
                            };
                        },
                        cache: true
                    }
                });

                $('button[type="submit"]').on('click', function() {
                    $(this).addClass('cursor-not-allowed').attr('disabled', 'disabled');
                    $(this).parents('form').submit();
                });
            });

            @if (Session::has('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: '{{ Session::get('success') }}'
                })
            @endif

            @if (Session::has('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: '{{ Session::get('error') }}'
                })
            @endif
        </script>
    @endpush
</x-app-layout>
