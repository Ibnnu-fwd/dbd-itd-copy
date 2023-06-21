<x-app-layout>
    <x-breadcrumb name="user" />
    <x-card-container>
        <div class="xl:grid grid-cols-5 gap-4">
            <div>
                <img class="w-full h-40 rounded-lg object-cover" id="profile_picture_thumbnail"
                    src="{{ auth()->user()->profile_picture
                        ? asset('storage/profile-picture/' . auth()->user()->profile_picture)
                        : asset('assets/images/noimage.jpg') }}"
                    alt="Large avatar">
                <div class="mt-4">
                    <x-button type="button" id="btnUpload" color="gray" icon="fas fa-upload"
                        class="w-full justify-center">
                        Unggah Foto
                    </x-button>
                </div>
                <input type="file" name="profile_picture" id="profile_picture" hidden>
            </div>
            <div class="col-span-2 mt-4 md:mt-0">
                <form action="{{ route('admin.user.update', auth()->user()->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <p class="text-xs 2xl:text-sm font-semibold mb-4">
                        Informasi Pribadi
                    </p>
                    <x-input id="name" name="name" type="text" label="Nama Lengkap" :value="auth()->user()->name"
                        required />
                    <x-select id="sex" name="sex" label="Jenis Kelamin" isFit="true" required>
                        <option value="1" {{ auth()->user()->sex == '1' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="2" {{ auth()->user()->sex == '2' ? 'selected' : '' }}>Perempuan</option>
                    </x-select>
                    <x-input-datepicker id="birthday" name="birthday" label="Tanggal Lahir" :value="date('m/d/Y', strtotime(auth()->user()->birthday))"
                        required />
                    <x-input id="phone" name="phone" type="text" label="Nomor Telepon" :value="auth()->user()->phone"
                        required />
                    <x-textarea id="address" name="address" label="Alamat" :value="auth()->user()->address" required />
                    <div class="flex justify-end mt-4">
                        <x-button type="submit" icon="fas fa-save" class="bg-primary w-full md:w-auto">
                            Simpan Perubahan
                        </x-button>
                    </div>
                </form>
            </div>
            <div class="col-span-2">
                <p class="text-xs 2xl:text-sm font-semibold mb-4">
                    Akun Pengguna
                </p>
                <form action="{{ route('admin.user.update-user-account', auth()->user()->id) }}" method="POST">
                    @csrf
                    <x-input id="email" name="email" type="email" label="Email" :value="auth()->user()->email" required />
                    <x-input id="old_password" name="old_password" type="password" label="Password Lama" required />
                    <x-input id="new_password" name="new_password" type="password" label="Password Baru" required />
                    <x-input id="new_password_confirmation" name="new_password_confirmation" type="password"
                        label="Konfirmasi Password Baru" required />
                    <div class="flex justify-end mt-4">
                        <x-button type="submit" icon="fas fa-save" class="bg-primary w-full md:w-auto">
                            Simpan Perubahan
                        </x-button>
                    </div>
                </form>
            </div>
        </div>
    </x-card-container>

    @push('js-internal')
        <script>
            $(function() {
                $('#btnUpload').on('click', function() {
                    $('input[name="profile_picture"]').click();

                    $('#profile_picture').on('change', function() {
                        var file = $(this)[0].files[0];
                        var reader = new FileReader();
                        reader.onload = function(e) {
                            $('#profile_picture_thumbnail').attr('src', e.target.result);
                        }
                        reader.readAsDataURL(file);

                        // confirm
                        Swal.fire({
                            title: 'Apakah Anda yakin?',
                            text: "Foto profil akan diubah!",
                            icon: 'warning',
                            showCancelButton: true,
                        }).then((result) => {
                            if (result.isConfirmed) {
                                let file = $('#profile_picture')[0].files[0];
                                let formData = new FormData();
                                formData.append('id', "{{ auth()->user()->id }}");
                                formData.append('profile_picture', file);
                                formData.append('_token', "{{ csrf_token() }}");

                                $.ajax({
                                    url: "{{ route('admin.user.update-profile-picture') }}",
                                    method: 'POST',
                                    data: formData,
                                    contentType: false,
                                    processData: false,
                                    success: function(response) {
                                        if (response.status == 'success') {
                                            Swal.fire({
                                                title: 'Berhasil!',
                                                text: "Foto profil berhasil diubah!",
                                                icon: 'success',
                                                showCancelButton: false,
                                            }).then((result) => {
                                                if (result.isConfirmed) {
                                                    window.location
                                                        .reload();
                                                }
                                            });
                                        } else {
                                            Swal.fire({
                                                title: 'Gagal!',
                                                text: "Foto profil gagal diubah!",
                                                icon: 'error',
                                                showCancelButton: false,
                                            });
                                        }
                                    }
                                });
                            }
                        })
                    });
                });

                @if (Session::has('success'))
                    Swal.fire({
                        title: 'Berhasil!',
                        text: "{{ Session::get('success') }}",
                        icon: 'success',
                        showCancelButton: false,
                    });
                @endif

                @if (Session::has('error'))
                    Swal.fire({
                        title: 'Gagal!',
                        text: "{{ Session::get('error') }}",
                        icon: 'error',
                        showCancelButton: false,
                    });
                @endif
            });
        </script>
    @endpush
</x-app-layout>
