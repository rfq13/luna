@extends('templates/main')
@section('css')
    <link rel="stylesheet" href="{{ asset('css/manage_product/product/style.css') }}">
@endsection
@section('content')
    <div class="row page-title-header">
        <div class="col-12">
            <div class="page-header d-flex justify-content-between align-items-center">
                <h4 class="page-title">Daftar Kredit</h4>
                <div class="d-flex justify-content-start">
                    <div class="dropdown">
                        <button class="btn btn-icons btn-inverse-primary btn-filter shadow-sm" type="button"
                            id="dropdownMenuIconButton1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="mdi mdi-filter-variant"></i>
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuIconButton1">
                            <h6 class="dropdown-header">Urut Berdasarkan :</h6>
                            <div class="dropdown-divider"></div>
                            <a href="#" class="dropdown-item filter-btn" data-filter="kode_barang">Kode Barang</a>
                            <a href="#" class="dropdown-item filter-btn" data-filter="jenis_barang">Jenis Barang</a>
                            <a href="#" class="dropdown-item filter-btn" data-filter="nama_barang">Nama Barang</a>
                            <a href="#" class="dropdown-item filter-btn" data-filter="berat_barang">Berat Barang</a>
                            <a href="#" class="dropdown-item filter-btn" data-filter="harga">Harga Barang</a>
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
                                    <input type="text" class="form-control" name="search" placeholder="Cari barang">
                                </div>
                            </div>
                        </div>
                    </div>
                    <a href="{{ url('/product/new') }}" class="btn btn-icons btn-inverse-primary btn-new ml-2">
                        <i class="mdi mdi-plus"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="row modal-group">
        <div class="modal fade" id="bayarModal" tabindex="-1" role="dialog" aria-labelledby="bayarModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="{{ url('/kredit/') }}" data-url="{{ url('/kredit/') }}" method="post" name="bayar_form">
                        <div class="modal-header">
                            <h5 class="modal-title" id="bayarModalLabel">Bayar Cicilan <span></span></h5>
                            <button type="button" class="close close-btn" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body" id="edit-modal-body">
                            @method('put')
                            @csrf
                            <div class="form-group row">
                                <label class="col-lg-3 col-md-3 col-sm-12 col-form-label font-weight-bold pt-1">Kode
                                    Transaksi</label>
                                <div class="col-lg-9 col-md-9 col-sm-12">
                                    <span class="kode_transaksi"></span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-3 col-md-3 col-sm-12 col-form-label font-weight-bold pt-1">Total Transaksi</label>
                                <div class="col-lg-9 col-md-9 col-sm-12">
                                    <span class="amount"></span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-3 col-md-3 col-sm-12 col-form-label font-weight-bold pt-1">Sisa Cicilan</label>
                                <div class="col-lg-9 col-md-9 col-sm-12">
                                    <span class="remaining_installment"></span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-3 col-md-3 col-sm-12 col-form-label font-weight-bold pt-1">Jatuh Tempo</label>
                                <div class="col-lg-9 col-md-9 col-sm-12">
                                    <span class="due_date"></span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-3 col-md-3 col-sm-12 col-form-label font-weight-bold pt-1">Nominal</label>
                                <div class="col-lg-9 col-md-9 col-sm-12">
                                    <input type="text" class="form-control" name="repayment_amount" required>
                                    <input type="hidden" class="form-control" name="repayment_code" value="T{{ date('ddmYHis') }}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-3 col-md-3 col-sm-12 col-form-label font-weight-bold pt-1">Keterangan</label>
                                <div class="col-lg-9 col-md-9 col-sm-12">
                                    <textarea name="repayment_description" id="" cols="30" rows="10" class="form-control"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer" id="edit-modal-footer">
                            <button type="submit" class="btn btn-update"><i class="mdi mdi-content-save"></i>
                                Bayar</button>
                        </div>
                        <div class="modal-footer" id="scan-modal-footer" hidden="">
                            <button type="button"
                                class="btn btn-primary btn-sm font-weight-bold rounded-0 btn-continue">Lanjutkan</button>
                            <button type="button"
                                class="btn btn-outline-secondary btn-sm font-weight-bold rounded-0 btn-repeat">Ulangi</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12 grid-margin">
            <div class="card card-noborder b-radius">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 table-responsive">
                            <table class="table table-custom">
                                <thead>
                                    <tr>
                                        <th>Transaksi</th>
                                        <th>Nama Pelanggan</th>
                                        <th>Total</th>
                                        <th>Dp</th>
                                        <th>Tenor</th>
                                        <th>Jatuh Tempo</th>
                                        <th>Sisa Cicilan</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($kredits as $kredit)
                                        <tr>
                                            <td>
                                                <span class="kd-barang-field kode-trx-kredit">{{ $kredit->transaction_code }}</span>
                                            </td>
                                            <td>{{ $kredit->customer->nama }}</td>
                                            <td>
                                                <span class="ammount-box bg-green">
                                                <i class="mdi mdi-coin"></i>
                                                </span>Rp. {{ number_format($kredit->amount, 2, ',', '.') }}
                                            </td>
                                            <td>{{ $kredit->dp }}</td>
                                            <td>{{ $kredit->tenor }} {{ $kredit->tenor_unit }}</td>
                                            <td>{{ $kredit->due_date }}</td>
                                            <td>
                                                <span class="ammount-box bg-green">
                                                    <i class="mdi mdi-coin"></i>
                                                </span>Rp.
                                                {{ number_format($kredit->remaining_installment, 2, ',', '.') }}
                                            </td>
                                            <td>
                                                <button type="button"
                                                    class="btn btn-bayar btn-icons btn-rounded btn-secondary"
                                                    data-toggle="modal" data-target="#bayarModal"
                                                    data-bayar="{{ $kredit->id }}">
                                                    <i class="mdi mdi-pencil"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        @if ($kredit->repayments->count() > 0)
                                        <tr style="display:none" class="cicilan{{ $kredit->transaction_code }}">
                                            <td colspan="7">
                                                <div class="container-fluid d-flex justify-content-center">
                                                    <div class="card card-noborder b-radius">
                                                        <div class="card-body">
                                                          <div class="row">
                                                            <div class="col-12 table-responsive">
                                                                <table class="table-custom">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>Kode</th>
                                                                            <th>Nominal</th>
                                                                            <th>Tanggal</th>
                                                                            <th>Keterangan</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        @foreach ($kredit->repayments as $repayment)
                                                                        <tr>
                                                                            <td>{{ $repayment->kode_pelunasan }}</td>
                                                                            <td>{{ $repayment->nominal }}</td>
                                                                            <td>{{ Carbon\Carbon::parse($repayment->created_at)->format('Y-m-d H:i:s') }}</td>
                                                                            <td>{{ $repayment->keterangan ?? '-' }}</td>
                                                                        </tr>
                                                                        @endforeach
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                          </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        @endif
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
    <script src="{{ asset('plugins/js/quagga.min.js') }}"></script>
    <script src="{{ asset('js/kredit/script.js') }}"></script>
    <script type="text/javascript">
        $('#supplier_opt').selectpicker({
            liveSearch: true,
            width: '100%',
            noneResultsText: 'tidak ada'
        });

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

        @if ($message = Session::get('import_success'))
            swal(
            "Berhasil!",
            "{{ $message }}",
            "success"
            );
        @endif

        @if ($message = Session::get('update_failed'))
            swal(
            "",
            "{{ $message }}",
            "error"
            );
        @endif

        @if ($message = Session::get('supply_system_status'))
            swal(
            "",
            "{{ $message }}",
            "success"
            );
        @endif

        $(document).on('click', '.filter-btn', function(e) {
            e.preventDefault();
            var data_filter = $(this).attr('data-filter');
            $.ajax({
                method: "GET",
                url: "{{ url('/kredit/filter') }}/" + data_filter,
                success: function(data) {
                    $('tbody').html(data);
                }
            });
        });

        $(".kode-trx-kredit").on("click", function (e) {
            e.preventDefault();
            const target = $(this);

            const cicilan = $(".cicilan"+target.text())

            const onhide = cicilan.css('display') == 'none';
            if(onhide){
                cicilan.show();
            }else{
                cicilan.hide();
            }

        })

        $("#bayarModal").on('hide.bs.modal', function (e) {
            const form = $("[name='bayar_form']")
            form.attr("action", form.data("url"))

            $(this).find(".kode_transaksi").html("")
            $(this).find(".amount").html("")
            $(this).find(".remaining_installment").html("")
            $(this).find(".due_date").html("")
        })

        $(document).on('click', '.btn-bayar', function() {
            var data_bayar = $(this).attr('data-bayar');
            $.ajax({
                method: "GET",
                url: "{{ url('/kredit') }}/" + data_bayar,
                success: function(response) {
                    const form = $("[name='bayar_form']")
                    form.attr("action", form.data("url") + "/" + data_bayar)
                    $("#bayarModal .kode_transaksi").html(response.transaction_code)
                    $("#bayarModal .amount").html('Rp.'+toRupiah(response.amount))
                    $("#bayarModal .remaining_installment").html('Rp.' + toRupiah(response.remaining_installment))
                    $("#bayarModal .due_date").html(response.due_date)
                    // $('input[name=id]').val(response.product.id);
                    // $('input[name=kode_barang]').val(response.product.kode_barang);
                    // $('input[name=nama_barang]').val(response.product.nama_barang);
                    // $('select[name=supplier]').val(response.product.supplier_id).selectpicker(
                    //     'refresh');
                    // $('input[name=stok]').val(response.product.stok);
                    // $('input[name=harga_ecer]').val(response.product.harga_ecer);
                    // $('input[name=harga_khusus]').val(response.product.harga_khusus);
                    // $('input[name=harga_extra]').val(response.product.harga_extra);
                    // $('input[name=harga_grosir]').val(response.product.harga_grosir);
                    // var berat_barang = response.product.berat_barang.split(" ");
                    // $('input[name=berat_barang]').val(berat_barang[0]);
                    // $('select[name=jenis_barang] option[value="' + response.product.jenis_barang + '"]')
                    //     .prop('selected', true);
                    // $('select[name=satuan_berat] option[value="' + berat_barang[1] + '"]').prop(
                    //     'selected', true);
                    // $('select[name=satuan_produk]').val(response.product.unit_id);
                    // validator.resetForm();
                }
            });
        });

        $(document).on('click', '.btn-delete', function(e) {
            e.preventDefault();
            var data_delete = $(this).attr('data-delete');
            swal({
                    title: "Apa Anda Yakin?",
                    text: "Data barang akan terhapus, klik oke untuk melanjutkan",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                .then((willDelete) => {
                    if (willDelete) {
                        window.open("{{ url('/product/delete') }}/" + data_delete, "_self");
                    }
                });
        });
    </script>
@endsection
