@extends('templates/main')
@section('css')
    <link rel="stylesheet" href="{{ asset('css/manage_account/account/style.css') }}">
@endsection
@section('content')
    <form action="#" method="post" id="form_delete">
        @csrf
        @method("delete")
    </form>
    <div class="row page-title-header">
        <div class="col-12">
            <div class="page-header d-flex justify-content-between align-items-center">
                <h4 class="page-title">Daftar Cabang</h4>
                <div class="d-flex justify-content-start">
                    <div class="dropdown">
                        <button class="btn btn-icons btn-inverse-primary btn-filter shadow-sm" type="button"
                            id="dropdownMenuIconButton1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="mdi mdi-filter-variant"></i>
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuIconButton1">
                            <h6 class="dropdown-header">Urut Berdasarkan :</h6>
                            <div class="dropdown-divider"></div>
                            <a href="#" class="dropdown-item filter-btn" data-filter="nama">Nama</a>
                            <a href="#" class="dropdown-item filter-btn" data-filter="email">Email</a>
                            <a href="#" class="dropdown-item filter-btn" data-filter="role">Posisi</a>
                        </div>
                    </div>
                    <div class="dropdown dropdown-search">
                        <button class="btn btn-icons btn-inverse-primary btn-filter shadow-sm ml-2" type="button"
                            id="dropdownMenuIconButton1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="mdi mdi-magnify"></i>
                        </button>
                        <div class="dropdown-menu search-dropdown" aria-labelledby="dropdownMenuIconButton1">
                            <div class="row">
                                <div class="col-11">
                                    <input type="text" class="form-control" name="search" placeholder="Cari akun">
                                </div>
                            </div>
                        </div>
                    </div>
                    <a href="{{ url('/branch/create') }}" class="btn btn-icons btn-inverse-primary btn-new ml-2">
                        <i class="mdi mdi-plus"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="row modal-group">
        <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="{{ url('/branch/update') }}" method="post" enctype="multipart/form-data"
                        name="update_form">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editModalLabel">Edit Cabang</h5>
                            <button type="button" class="close close-btn" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            @csrf
                            <div class="row">
                                <div class="col-12" hidden="">
                                    <input type="text" name="id">
                                </div>
                            </div>
                            <div class="form-group row mt-4">
                                <label class="col-3 col-form-label font-weight-bold">Nama</label>
                                <div class="col-9">
                                    <input type="text" class="form-control" name="name">
                                </div>
                                <div class="col-9 offset-3 error-notice" id="name_error"></div>
                            </div>
                            <div class="form-group row">
                                <label class="col-3 col-form-label font-weight-bold">Alamat</label>
                                <div class="col-9">
                                    <textarea type="text" class="form-control" name="address"
                                        placeholder="Masukkan alamat cabang"></textarea>
                                </div>
                                <div class="col-12 error-notice" id="address_error"></div>
                            </div>
                            <div class="form-group row">
                                <label class="col-3 col-form-label font-weight-bold">Admin</label>
                                <div class="col-9">
                                    <select class="form-control" name="branch_admin" title="pilih admin">
                                    </select>
                                </div>
                                <div class="col-12 error-notice" id="branch_admin_error"></div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-update"><i class="mdi mdi-content-save"></i>
                                Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 grid-margin">
            <div class="card card-noborder b-radius">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 table-responsive">
                            <table class="table table-custom">
                                <thead>
                                    <tr>
                                        <th>Nama</th>
                                        <th>Admin</th>
                                        <th>Alamat</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($admins as $admin)
                                        <tr>
                                            <td>
                                                <span>{{ $admin->branch->name }}</span>
                                            </td>
                                            <td>
                                                @if ($admin->role == 'admin')
                                                    <span class="btn admin-span">{{ $admin->nama }}</span>
                                                @else
                                                    <span class="btn kasir-span">{{ $admin->nama }}</span>
                                                @endif
                                            </td>
                                            <td>{{ $admin->branch->address }}</td>
                                            <td>
                                                <button type="button"
                                                    class="btn btn-edit btn-icons btn-rounded btn-secondary"
                                                    data-toggle="modal" data-target="#editModal"
                                                    data-edit="{{ $admin->branch_id }}">
                                                    <i class="mdi mdi-pencil"></i>
                                                </button>
                                                <button type="button" data-delete="{{ $admin->branch->id }}"
                                                    class="btn btn-icons btn-rounded btn-secondary ml-1 btn-delete">
                                                    <i class="mdi mdi-close"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('script')
    <script src="{{ asset('js/branch/main.js') }}"></script>
    <script type="text/javascript">
        @if ($message = Session::get('create_success'))
            swal(
            "Berhasil!",
            "{{ $message }}",
            "success"
            );
        @endif

        @if ($message = Session::get('update_success'))
            swal(
            "Berhasil!",
            "{{ $message }}",
            "success"
            );
        @endif

        @if ($message = Session::get('delete_success'))
            swal(
            "Berhasil!",
            "{{ $message }}",
            "success"
            );
        @endif

        @if ($message = Session::get('both_error'))
            swal(
            "",
            "{{ $message }}",
            "error"
            );
        @endif

        @if ($message = Session::get('email_error'))
            swal(
            "",
            "{{ $message }}",
            "error"
            );
        @endif

        @if ($message = Session::get('username_error'))
            swal(
            "",
            "{{ $message }}",
            "error"
            );
        @endif

        $(document).on('click', '.filter-btn', function(e) {
            e.preventDefault();
            var data_filter = $(this).attr('data-filter');
            $.ajax({
                method: "GET",
                url: "{{ url('/branch/filter') }}/" + data_filter,
                success: function(data) {
                    $('tbody').html(data);
                }
            });
        });

        const editModal = $("#editModal");

        $(document).on('click', '.btn-edit', function() {
            var data_edit = $(this).attr('data-edit');
            $.ajax({
                method: "GET",
                url: "{{ url('/branch/edit') }}/" + data_edit,
                success: function(response) {
                    const {
                        branch,
                        admins
                    } = response;

                    options = '';
                    for (let i = 0; i < admins.length; i++) {
                        const admin = admins[i];
                        options +=
                            `
                            <option value="${admin.id}" ${admin.branch_id == branch.id ? 'selected' : ''} >
                                ${admin.nama}
                            </option>
                            `;
                    }

                    editModal.find("select").html(options).selectpicker({
                        liveSearch: true,
                        width: '100%',
                        noneResultsText: 'tidak ada',
                        styleBase: "form-control py-2"
                    });

                    editModal.find("input[name=id]").val(branch.id)
                    editModal.find("input[name=name]").val(branch.name)
                    editModal.find("textarea[name=address]").val(branch.address)
                }
            });
        });

        editModal.on("hide.bs.modal", function(e) {
            editModal.find("form")[0].reset()
            editModal.find("select").val("").selectpicker("destroy");
        })

        $(document).on('click', '.btn-delete', function(e) {
            e.preventDefault();
            var data_delete = $(this).attr('data-delete');
            swal({
                    title: "Apa Anda Yakin?",
                    text: "Data cabang akan terhapus, klik oke untuk melanjutkan",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                .then((willDelete) => {
                    if (willDelete) {
                        $("#form_delete")
                            .append(`<input type="hidden" name="id" value="${data_delete}">`)
                            .attr("action", `{{ url('/branch/delete') }}`)
                            .submit()
                    }
                });
        });

        $(document).on('click', '.btn-delete-img', function() {
            $(".img-edit").attr("src", "{{ asset('pictures') }}/default.jpg");
            $('input[name=nama_foto]').val('default.jpg');
        });
    </script>
@endsection
