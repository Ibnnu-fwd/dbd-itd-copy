<x-app-layout>
    <x-breadcrumb name="village.create" />
    <div class="lg:w-1/2 w-full">
        <x-card-container>
            <form action="{{ route('admin.village.store') }}" method="POST">
                @csrf
                <x-input id="name" name="name" type="text" label="Nama desa" required />
                <x-select id="district_id" name="district_id" label="Kecamatan" isFit="" required />
                <x-button type="submit" class="bg-primary">Simpan</x-button>
            </form>
        </x-card-container>
    </div>

    @push('js-internal')
        <script>
            $(function() {
                $('#district_id').select2({
                    placeholder: 'Pilih kecamatan',
                    allowClear: true,
                    ajax: {
                        url: "{{route('admin.district.list')}}",
                        method: 'POST',
                        dataType: 'json',
                        delay: 250,
                        data: function(params) {
                            return {
                                _token: '{{ csrf_token() }}',
                                search: params.term
                            }
                        },
                        processResults: function(response) {
                            return {
                                results: response
                            }
                        },
                        cache: true
                    }
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
