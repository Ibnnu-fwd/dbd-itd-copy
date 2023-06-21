<x-app-layout>
    <x-breadcrumb name="ksh.member" />
    <x-card-container>
        <div class="flex flex-col gap-3 md:flex-row md:justify-end mb-4">
            <x-button type="button" data-modal-toggle="defaultModal" color="gray" type="button" class="justify-center">
                Tambah
            </x-button>
        </div>
        <table id="memberTable">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nama</th>
                    <th>Gender</th>
                    <th>No. Telefon</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Bergabung Sejak</th>
                    <th>Status</th>
                </tr>
            </thead>
        </table>
    </x-card-container>

    <!-- Modal -->
    <div id="defaultModal" tabindex="-1" aria-hidden="true"
        class="hidden overflow-y-auto overflow-x-hidden fixed inset-0 z-50 flex items-center justify-center">
        <div class="relative p-4 w-screen max-w-2xl">
            <!-- Modal content -->
            <div class="relative p-4 bg-white rounded-lg shadow dark:bg-gray-800 sm:p-5">
                <!-- Modal header -->
                <div
                    class="flex justify-between items-center pb-4 mb-4 rounded-t border-b sm:mb-5 dark:border-gray-600">
                    <h3 class="text-sm font-semibold text-gray-900 dark:text-white">
                        Tambah Member
                    </h3>
                    <button type="button"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white"
                        data-modal-toggle="defaultModal">
                        <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                clip-rule="evenodd"></path>
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
                <!-- Modal body -->
                <form action="{{ route('admin.ksh.member.store') }}" method="POST">
                    @csrf
                    <div class="xl:grid grid-cols-2 gap-x-4">
                        <x-input id="name" type="text" name="name" label="Nama Lengkap" required />
                        <x-select id="sex" name="sex" label="Jenis Kelamin" required>
                            <option value="1">Laki-laki</option>
                            <option value="2">Perempuan</option>
                        </x-select>
                        <x-input id="phone" type="text" name="phone" label="No. Telefon" required />
                        <x-input-datepicker id="birthday" name="birthday" label="Tanggal Lahir" required />
                        <x-textarea id="address" name="address" label="Alamat" required />
                        <x-input id="email" type="email" name="email" label="Email" required />
                        <span class="text-xs 2xl:text-sm"><span class="text-error">*</span> Password akan dikirimkan ke
                            email yang dimasukkan</span>
                    </div>
                    <div class="flex flex-col gap-2 md:flex-row md:justify-end mt-6 items-end">
                        <x-button type="button" data-modal-toggle="defaultModal" color="gray"
                            class="justify-center w-full md:w-auto">
                            Batal
                        </x-button>
                        <x-button type="submit" class="bg-primary justify-center w-full md:w-auto">
                            Simpan
                        </x-button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('js-internal')
        <script>
            function changeStatus(id)
            {
                let status = $('#status-' + id).val();
                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Anda akan mengubah status member ini!",
                    icon: 'warning',
                    showCancelButton: true,
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ route('admin.ksh.member.change-status') }}",
                            type: "POST",
                            data: {
                                id: id,
                                status: status,
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                if(response.status === 'success')
                                {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Berhasil',
                                        text: response.message,
                                        showConfirmButton: false,
                                        timer: 1500
                                    }).then(() => {
                                        $('#memberTable').DataTable().ajax.reload();
                                    });
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Gagal',
                                        text: response.message,
                                    });
                                }
                            },
                        });
                    }
                });
            }

            $(function() {
                $('#memberTable').DataTable({
                    processing: true,
                    serverSide: true,
                    responsive: true,
                    autoWidth: false,
                    ajax: "{{ route('admin.ksh.member') }}",
                    columns: [{
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex',
                            orderable: false,
                            searchable: false
                        }, {
                            data: 'name',
                            name: 'name'
                        },
                        {
                            data: 'sex',
                            name: 'sex'
                        },
                        {
                            data: 'phone',
                            name: 'phone'
                        },
                        {
                            data: 'email',
                            name: 'email'
                        },
                        {
                            data: 'role',
                            name: 'role'
                        },
                        {
                            data: 'created_at',
                            name: 'created_at'
                        },
                        {
                            data: 'action',
                            name: 'action'
                        },
                    ],
                });

                $('form').submit(function() {
                    $(this).find('button[type=submit]').html(
                        '<i class="fas fa-spinner fa-spin mr-2"></i> Memproses...');
                    $(this).find('button[type=submit]').prop('disabled', true);
                });

                @if (Session::has('success'))
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: '{{ Session::get('success') }}',
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        $('#memberTable').DataTable().ajax.reload();
                    });
                @endif

                @if (Session::has('error'))
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: '{{ Session::get('error') }}',
                    });
                @endif
            });
        </script>
    @endpush
</x-app-layout>
