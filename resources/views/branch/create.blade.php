@extends('templates/main')
@section('css')
    <link rel="stylesheet" href="{{ asset('css/manage_account/new_account/style.css') }}">
@endsection
@section('content')
    <div class="row page-title-header">
        <div class="col-12">
            <div class="page-header d-flex justify-content-start align-items-center">
                <div class="quick-link-wrapper d-md-flex flex-md-wrap">
                    <ul class="quick-links">
                        <li><a href="{{ url('branch') }}">Daftar Cabang</a></li>
                        <li><a href="javascript:void(0)">Buat Cabang Baru</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card card-noborder b-radius">
                <div class="card-body">
                    <form action="{{ url('branch') }}" method="post" name="create_form" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group row">
                            <label class="col-12 font-weight-bold col-form-label">Nama Cabang<span
                                    class="text-danger">*</span></label>
                            <div class="col-12">
                                <input type="text" class="form-control" name="branch_name"
                                    placeholder="Masukkan Nama Cabang">
                            </div>
                            <div class="col-12 error-notice" id="branch_name_error"></div>
                        </div>
                        <div class="form-group row">
                            <label class="col-12 font-weight-bold col-form-label">Alamat <span
                                    class="text-danger">*</span></label>
                            <div class="col-12">
                                <textarea type="text" class="form-control" name="address"
                                    placeholder="Masukkan alamat cabang"></textarea>
                            </div>
                            <div class="col-12 error-notice" id="address_error"></div>
                        </div>
                        <div class="form-group row">
                            <label class="col-12 font-weight-bold col-form-label admin-cabang">Admin Cabang <span
                                    class="text-danger">*</span></label>
                            <div class="col-12">
                                <select class="form-control" name="branch_admin" title="pilih admin">
                                    @foreach ($admins as $admin)
                                        <option value="{{ $admin->id }}">{{ $admin->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12 error-notice" id="branch_admin_error"></div>
                        </div>
                        <div class="form-group row">
                            <div class="col-12">
                                <a href="javascript:void(0)" class="font-weight-bold float-right"
                                    onclick="createNewAdmin(event)">
                                    Buat akun admin baru
                                    <span class="text-danger">+</span>
                                </a>
                            </div>
                        </div>

                        <div class="create-new-admin" style="display: none">
                            <div class="form-group row">
                                <label class="col-12 font-weight-bold col-form-label">Foto Profil</label>
                                <div class="col-12 d-flex flex-row align-items-center mt-2 mb-2">
                                    <img src="{{ asset('pictures/default.jpg') }}" class="default-img mr-4"
                                        id="preview-foto">
                                    <div class="btn-action">
                                        <input type="file" name="foto" id="foto" hidden="">
                                        <button class="btn btn-sm upload-btn mr-1" type="button">Upload Foto</button>
                                        <button class="btn btn-sm delete-btn" type="button">Hapus</button>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-12 font-weight-bold col-form-label">Nama <span
                                        class="text-danger">*</span></label>
                                <div class="col-12">
                                    <input type="text" class="form-control" name="nama" placeholder="Masukkan Nama">
                                </div>
                                <div class="col-12 error-notice" id="nama_error"></div>
                            </div>
                            <div class="form-group row">
                                <label class="col-12 font-weight-bold col-form-label">Email <span
                                        class="text-danger">*</span></label>
                                <div class="col-12">
                                    <input type="email" class="form-control" name="email" placeholder="Masukkan Email">
                                </div>
                                <div class="col-12 error-notice" id="email_error"></div>
                            </div>
                            <div class="form-group row">
                                <label class="col-12 font-weight-bold col-form-label">Username <span
                                        class="text-danger">*</span></label>
                                <div class="col-12">
                                    <input type="text" class="form-control" name="username"
                                        placeholder="Masukkan Username">
                                </div>
                                <div class="col-12 error-notice" id="username_error"></div>
                            </div>
                            <div class="form-group row">
                                <label class="col-12 font-weight-bold col-form-label">Password <span
                                        class="text-danger">*</span></label>
                                <div class="col-12">
                                    <input type="password" class="form-control" name="password"
                                        placeholder="Masukkan Password">
                                </div>
                                <div class="col-12 error-notice" id="password_error"></div>
                            </div>
                            {{-- <div class="form-group row">
                                <label class="col-12 font-weight-bold col-form-label">Posisi <span
                                        class="text-danger">*</span></label>
                                <div class="col-12">
                                    <select class="form-control" name="role">
                                        <option value="">-- Pilih Posisi --</option>
                                        <option value="admin">Admin</option>
                                        <option value="kasir">Kasir</option>
                                    </select>
                                </div>
                                <div class="col-12 error-notice" id="role_error"></div>
                            </div> --}}
                        </div>
                        <div class="row mt-5">
                            <div class="col-12 d-flex justify-content-end">
                                <button class="btn simpan-btn btn-sm" type="submit"><i class="mdi mdi-content-save"></i>
                                    Simpan</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script src="{{ asset('js/branch/script.js') }}"></script>
    <script type="text/javascript">
        $('select[name=branch_admin]').selectpicker({
            liveSearch: true,
            width: '100%',
            noneResultsText: 'tidak ada',
            styleBase: "form-control py-2"
        });

        @if ($message = Session::get('both_error'))
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

        @if ($message = Session::get('email_error'))
            swal(
            "",
            "{{ $message }}",
            "error"
            );
        @endif

        $(document).on('click', '.delete-btn', function() {
            $("#preview-foto").attr("src", "{{ asset('pictures') }}/default.jpg");
        });

        var isCreateNewAdmin = 0;

        function createNewAdmin(e) {
            var formExpand = $(".create-new-admin")
            var labelAdminCabang = $("label.admin-cabang")

            span = $(e.target).find("span")

            if (isCreateNewAdmin) {
                formExpand.slideUp()
                isCreateNewAdmin = 0;
                removeValidations()
                span.text("+");
                labelAdminCabang.find("span").html("*")
                labelAdminCabang.parent().slideDown()
            } else {
                labelAdminCabang.find("span").html("")
                labelAdminCabang.parent().slideUp().find("#branch_admin_error").html("")
                formExpand.slideDown();
                // validations()
                addValidations();
                isCreateNewAdmin = 1;
                span.text("-");
            }
        }

        function removeValidations() {
            createForm = $("form[name='create_form'] .create-new-admin");
            formInput = createForm.find("input")
            formSelect = createForm.find("select")

            for (let i = 0; i < formInput.length; i++) {
                let element = $(formInput[i]);
                myName = element.attr("name");
                if (myName == "_token" || myName == "foto") continue;
                element.rules('remove');
            }
            for (let i = 0; i < formSelect.length; i++) {
                element = formSelect[i];
                $(element).rules('remove');
            }
            $("select[name=branch_admin]").rules("add", rules['branch_admin']);
        }

        function addValidations() {
            createForm = $("form[name='create_form'] .create-new-admin");
            formInput = createForm.find("input")
            formSelect = createForm.find("select")

            for (let i = 0; i < formInput.length; i++) {
                let element = $(formInput[i]);
                myName = element.attr("name");
                if (myName == "_token" || myName == "foto") continue;
                myRules = rules[myName];

                element.rules('add', myRules);
            }
            for (let i = 0; i < formSelect.length; i++) {
                element = $(formSelect[i]);
                myName = element.attr("name");
                myRules = rules[myName];

                element.rules('add', myRules);
            }

            $("select[name=branch_admin]").rules("remove")
        }
    </script>

@endsection
