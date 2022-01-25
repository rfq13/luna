@extends('templates/main')
@section('css')
    <link rel="stylesheet" href="{{ asset('css/manage_account/account/style.css') }}">
@endsection
@section('content')
    <div class="row page-title-header">
        <div class="col-12">
            <div class="page-header d-flex justify-content-between align-items-center">
                <h4 class="page-title">List Supplier</h4>
                <div class="d-flex justify-content-start">
                    <div class="dropdown">
                        <button class="btn btn-icons btn-inverse-primary btn-filter shadow-sm" type="button"
                            id="dropdownMenuIconButton1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="mdi mdi-filter-variant"></i>
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuIconButton1">
                            <h6 class="dropdown-header">Urut Berdasarkan :</h6>
                            <div class="dropdown-divider"></div>
                            <a href="#" class="dropdown-item filter-btn" data-filter="name">Nama</a>
                            <a href="#" class="dropdown-item filter-btn" data-filter="total">Jumlah Produk</a>
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
                                    <input type="text" class="form-control" name="search" placeholder="Cari supplier">
                                </div>
                            </div>
                        </div>
                    </div>
                    <button data-toggle="modal" data-target="#editModal"
                        class="btn btn-icons btn-inverse-primary btn-new ml-2">
                        <i class="mdi mdi-plus"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="row modal-group">
        <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="{{ url('/supplier/update') }}" method="post" enctype="multipart/form-data"
                        name="update_form">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editModalLabel">Edit Supplier</h5>
                            <button type="button" class="close close-btn" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            @csrf
                            <input type="hidden" name="id">
                            <div class="form-group row mt-4">
                                <label class="col-3 col-form-label font-weight-bold">Nama</label>
                                <div class="col-9">
                                    <input type="text" class="form-control" name="name">
                                </div>
                                <div class="col-9 offset-3 error-notice" id="nama_error"></div>
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
        <div class="col-md-6 grid-margin">
            <div class="card card-noborder b-radius">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 table-responsive">
                            <table class="table table-custom">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Nama</th>
                                        <th>Jumlah Produk</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
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
    <script src="{{ asset('js/manage_product/supply_product/supplier.js') }}"></script>
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

        var urlGetData = "{{ url('/supplier/data') }}/";
        var urlEditData = "{{ url('/supplier/idspace/edit') }}/";
        var urlDeleteData = "{{ url('/supplier/delete') }}/";
    </script>
@endsection
