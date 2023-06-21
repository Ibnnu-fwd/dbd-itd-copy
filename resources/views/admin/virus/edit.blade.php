<x-app-layout>
    <x-breadcrumb name="virus.create" />
    <div class="lg:w-1/2 w-full">
        <x-card-container>
            <form action="{{ route('admin.virus.update', $virus->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <x-input id="name" name="name" type="text" label="Nama virus" :value="$virus->name" required />
                <x-textarea id="description" name="description" label="Deskripsi" :value="$virus->description" />
                <div id="image-preview" class="flex justify-center {{ $virus->image ? '' : 'hidden' }}">
                    <img id="preview-image" class="rounded w-1/2"
                        src="{{ $virus->image ? asset('storage/virus/' . $virus->image) : asset('assets/images/noimage.jpg') }}"
                        alt="preview image">
                </div>
                <x-input-file id="image" name="image" label="Gambar" />
                <x-button type="submit" class="bg-primary disabled">Simpan</x-button>
            </form>
        </x-card-container>
    </div>

    @push('js-internal')
        <script>
            $(function() {
                $('button[type="submit"]').on('click', function() {
                    $(this).addClass('cursor-not-allowed').attr('disabled', 'disabled');
                    $(this).parents('form').submit();
                });
                $('#image').on('change', function() {
                    let file = $(this).get(0).files;
                    let reader = new FileReader();
                    reader.readAsDataURL(file[0]);
                    reader.addEventListener("load", function(e) {
                        let image = e.target.result;
                        $('#image-preview').removeClass('hidden');
                        $('#preview-image').attr('src', image);
                    });
                })
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
