<x-app-layout>
    <x-breadcrumb name="floor-type.edit" :data="$floorType" />
    <div class="lg:w-1/2 w-full">
        <x-card-container>
            <form action="{{ route('admin.floor-type.update', $floorType->id) }}" method="POST">
                @csrf
                @method('PUT')
                <x-input id="name" name="name" type="text" label="Jenis lantai" :value="$floorType->name" required />
                <x-button type="submit" class="bg-primary">Simpan</x-button>
            </form>
        </x-card-container>
    </div>

    @push('js-internal')
        <script>
            $(function() {

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
