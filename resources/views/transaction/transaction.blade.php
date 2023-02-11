@extends('templates/main')
@section('css')
<link rel="stylesheet" href="{{ asset('css/transaction/style.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" integrity="sha512-SfTiTlX6kk+qitfevl/7LibUOeJWlt9rbyDn92a1DqWOw9vWG2MFoays0sgObmWazO5BQPiFucnnEAjpAB+/Sw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

<style>
    .invisible-input {
        border: none;
        outline: none;
        background: none;
    }
</style>
@endsection
@section('content')
<div class="row page-title-header">
  <div class="col-12">
    <div class="page-header d-flex justify-content-start align-items-center">
      <h4 class="page-title">Daftar Barang</h4>
    </div>
  </div>
</div>
<div class="row modal-group">
  <div class="modal fade" id="scanModal" tabindex="-1" role="dialog" aria-labelledby="scanModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="scanModalLabel">Scan Barcode</h5>
          <button type="button" class="close close-btn" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <div class="row">
              <div class="col-12">
                <div class="alert alert-danger kode_barang_error" role="alert" hidden="">
                  <i class="mdi mdi-information-outline"></i> Kode barang tidak tersedia
                </div>
              </div>
              <div class="col-12 text-center" id="area-scan">
              </div>
              <div class="col-12 barcode-result" hidden="">
                <h5 class="font-weight-bold">Hasil</h5>
                <div class="form-border">
                  <p class="barcode-result-text"></p>
                </div>
              </div>
            </div>
        </div>
        <div class="modal-footer" id="btn-scan-action" hidden="">
          <button type="button" class="btn btn-primary btn-sm font-weight-bold rounded-0 btn-continue">Lanjutkan</button>
          <button type="button" class="btn btn-outline-secondary btn-sm font-weight-bold rounded-0 btn-repeat">Ulangi</button>
        </div>
      </div>
    </div>
  </div>
  <div class="modal fade" id="tableModal" tabindex="-1" role="dialog" aria-labelledby="tableModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="tableModalLabel">Daftar Barang</h5>
          <button type="button" class="close close-btn" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-12">
              <div class="form-group">
                <input type="text" class="form-control" name="search" placeholder="Cari barang">
              </div>
            </div>
            <div class="col-12">
              <ul class="list-group product-list">
                @foreach($products as $product)
                @if($supply_system->status == true)
                @if($product->stok != 0)
                <li class="list-group-item d-flex justify-content-between align-items-center active-list">
                  <div class="text-group">
                    <p class="m-0">{{ $product->kode_barang }}</p>
                    <p class="m-0 txt-light">{{ $product->nama_barang }}</p>
                  </div>
                  <div class="d-flex align-items-center">
                    <span class="ammount-box bg-secondary mr-1"><i class="mdi mdi-cube-outline"></i></span>
                    <p class="m-0">{{ $product->stok }}</p>
                  </div>
                  <a href="#" class="btn btn-icons btn-rounded btn-inverse-outline-primary font-weight-bold btn-pilih" role="button"><i class="mdi mdi-chevron-right"></i></a>
                </li>
                @endif
                @else
                <li class="list-group-item d-flex justify-content-between align-items-center active-list">
                  <div class="text-group">
                    <p class="m-0">{{ $product->kode_barang }}</p>
                    <p class="m-0 txt-light">{{ $product->nama_barang }}</p>
                  </div>
                  <div class="d-flex align-items-center">
                    <span class="ammount-box bg-green mr-1"><i class="mdi mdi-coin"></i></span>
                    <p class="m-0">Rp. {{ number_format($product->harga_ecer,2,',','.') }}</p>
                  </div>
                  <a href="#" class="btn btn-icons btn-rounded btn-inverse-outline-primary font-weight-bold btn-pilih" role="button"><i class="mdi mdi-chevron-right"></i></a>
                </li>
                @endif
                @endforeach
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  @if ($message = Session::get('transaction_success'))
  <div class="modal fade" id="successModal" tabindex="-1" role="dialog" aria-labelledby="successModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-body bg-grey">
          <div class="row">
            @php
            $transaksi = \App\Transaction::where('transactions.kode_transaksi', '=', $message)
            ->select('transactions.*')
            ->first();

            // dd($transaksi, $message);
            @endphp
            <div class="col-12 text-center mb-4">
              <img src="{{ asset('gif/success4.gif') }}">
              <h4 class="transaction-success-text">Transaksi{{ $transaksi->is_kredit ? ' Kredit ' : ' ' }}Berhasil</h4>
            </div>
            <div class="col-12">
              <table class="table-receipt">
                <tr>
                  <td>
                    <span class="d-block little-td">Kode Transaksi</span>
                    <span class="d-block font-weight-bold">{{ $message }}</span>
                  </td>
                  <td>
                    <span class="d-block little-td">Tanggal</span>
                    <span class="d-block font-weight-bold">{{ date('d M, Y', strtotime($transaksi->created_at)) }}</span>
                  </td>
                </tr>
                <tr>
                  <td>
                    <span class="d-block little-td">Kasir</span>
                    <span class="d-block font-weight-bold">{{ $transaksi->kasir }}</span>
                  </td>
                  <td>
                    <span class="d-block little-td">Total</span>
                    <span class="d-block font-weight-bold text-success">Rp. {{ number_format($transaksi->total,2,',','.') }}</span>
                  </td>
                </tr>
              </table>
              <table class="table-summary mt-3">
                <tr>
                  <td class="line-td" colspan="2"></td>
                </tr>
                <tr>
                  <td class="little-td big-td">Bayar</td>
                  <td>Rp. {{ number_format($transaksi->bayar,2,',','.') }}</td>
                </tr>
                @if (!$transaksi->is_kredit)
                    <tr>
                        <td class="little-td big-td">Kembali</td>
                        <td>Rp. {{ number_format($transaksi->kembali,2,',','.') }}</td>
                    </tr>
                @endif
              </table>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-sm btn-close-modal" data-dismiss="modal">Tutup</button>
          <a href="{{ url('/transaction/receipt/' . $message) }}" target="_blank" class="btn btn-sm btn-cetak-pdf">Cetak Struk</a>
        </div>
      </div>
    </div>
  </div>
  @endif
</div>
<form method="POST" name="transaction_form" id="transaction_form" action="{{ url('/transaction/process') }}">
  @csrf
  <div class="row">
    <div class="col-lg-8 col-md-8 col-sm-12 mb-4">
      <div class="row">
        <div class="col-12 mb-4 bg-dark-blue">
          <div class="card card-noborder b-radius">
            <div class="card-body">
              <div class="row">
                <div class="col-12 d-flex justify-content-between align-items-center transaction-header">
                  <div class="d-flex justify-content-start align-items-center">
                    <div class="icon-holder">
                      <i class="mdi mdi-swap-horizontal"></i>
                    </div>
                    <div class="transaction-code ml-3">
                      <p class="m-0 text-white">Kode Transaksi</p>
                      <p class="m-0 text-white">T{{ date('dmYHis') }}</p>
                      <input type="text" name="kode_transaksi" value="T{{ date('dmYHis') }}" hidden="">
                    </div>
                  </div>
                  <div class="btn-group mt-h">
                    <button class="btn btn-search" type="button">
                      <i class="mdi mdi-magnify"></i>
                    </button>
                    <button class="btn btn-scan" data-toggle="modal" data-target="#scanModal" type="button">
                      <i class="mdi mdi-crop-free"></i>
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-12">
          <div class="card card-noborder b-radius">
            <div class="card-body">
              <div class="row">
                <div class="col-12 d-flex justify-content-start align-items-center">
                  <div class="cart-icon mr-3">
                    <i class="mdi mdi-cart-outline"></i>
                  </div>
                  <p class="m-0 text-black-50">Daftar Pesanan</p>
                </div>
                <div class="col-12 mt-3 table-responsive">
                  <table class="table table-checkout">
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-4 col-md-4 col-sm-12">
      <div class="card card-noborder b-radius">
        <div class="card-body">
          <div class="row">
            <div class="col-12 payment-1">
              <table class="table-payment-1">
                <tr>
                  <td class="text-left">Tanggal</td>
                  <td class="text-right">{{ date('d M, Y') }}</td>
                </tr>
                <tr>
                  <td class="text-left">Waktu</td>
                  <td class="text-right">{{ date('H:i') }}</td>
                </tr>
                <tr>
                  <td class="text-left">Kasir</td>
                  <td class="text-right">{{ auth()->user()->nama }}</td>
                </tr>
              </table>
            </div>
            <div class="col-12 payment-1 mt-1">
                <h4>Detail Customer</h4>
              <table class="table-payment-1">
                <tr>
                  <td class="text-left">Tipe</td>
                  <td class="text-right">
                    <select name="tipe_customer" id="" class="form-control tipe-customer">
                        <option value="ecer">Ecer</option>
                        <option value="grosir">Grosir</option>
                        <option value="khusus">Khusus</option>
                        <option value="extra">Extra</option>
                    </select>
                    </td>
                </tr>
                <tr>
                  <td class="text-left">NIK</td>
                  <td class="text-right">
                    <input type="text" class="form-control nik-customer" name="nik_customer" value="{{ old('nik_customer') }}" required>
                    <div class="nik-msg">
                    </div>
                  </td>
                </tr>
                <tr>
                  <td class="text-left">Nama</td>
                  <td class="text-right">
                    <input type="text" class="form-control" name="nama_customer" value="{{ old('nama_customer') }}" onkeyup="editCustomerState('nama',this.value)" required>
                    </td>
                </tr>
                <tr>
                  <td class="text-left">NPWP</td>
                  <td class="text-right">
                    <input type="text" class="form-control" name="npwp_customer" value="{{ old('npwp_customer') }}" onkeyup="editCustomerState('npwp',this.value)" required>
                    </td>
                </tr>
                <tr>
                  <td class="text-left">No Hp</td>
                  <td class="text-right">
                    <input type="text" class="form-control" name="nohp_customer" value="{{ old('nohp_customer') }}" onkeyup="editCustomerState('nohp',this.value)" required>
                    </td>
                </tr>
                <tr>
                  <td class="text-left">Alamat</td>
                  <td class="text-right">
                    <textarea name="alamat_customer" id="alamat_customer" cols="30" rows="10" class="form-control alamat-customer" onkeyup="editCustomerState('alamat',this.value)" required>{{ old('alamat_customer') }}</textarea></td>
                </tr>
              </table>
            </div>
            <div class="col-12 mt-4">
              <table class="table-payment-2">
                <tr>
                  <td class="text-left">
                    <span class="subtotal-td">Subtotal</span>
                    <span class="jml-barang-td">0 Barang</span>
                  </td>
                  <td class="text-right nilai-subtotal1-td">Rp. 0</td>
                  <td hidden=""><input type="text" class="nilai-subtotal2-td" name="subtotal" value="0"></td>
                </tr>
                @php
                    $ppn = \GS::get('ppn');
                @endphp
                @if ($ppn > 0)
                    <tr>
                        <td class="text-left">
                            <span class="ppn-td" data-ppn="{{ $ppn }}">Ppn ({{ $ppn }}%)</span>
                        </td>
                        <td class="text-right d-flex justify-content-end align-items-center pt-2">
                            <input type="number" class="form-control ppn-input mr-2" name="ppn" value="0" hidden="">
                            <span class="nilai-ppn-span mr-1"></span>
                        </td>
                    </tr>
                @endif
                <tr>
                  <td class="text-left">
                    <span class="diskon-td">Diskon</span>
                    <a href="#" class="ubah-diskon-td">Ubah diskon</a>
                    <a href="#" class="simpan-diskon-td" hidden="">Simpan</a>
                  </td>
                  <td class="text-right d-flex justify-content-end align-items-center pt-2">
                    <input type="number" class="form-control diskon-input mr-2" min="0" max="100" name="diskon" value="0" hidden="">
                    <span class="nilai-diskon-td mr-1">0</span>
                    <span>%</span>
                  </td>
                </tr>
                <tr>
                  <td colspan="2" class="text-center nilai-total1-td">Rp. 0</td>
                  <td hidden=""><input type="text" class="nilai-total2-td" name="total" value="0"></td>
                </tr>
              </table>
            </div>
            <div class="col-12 mt-2">
                <table class="table-payment-3">
                    <tr>
                        <td>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">Rp.</div>
                                </div>
                                <input type="text" class="form-control number-input input-notzero bayar-input" name="bayar" placeholder="Masukkan nominal bayar">
                            </div>
                            <div class="col-12 mt-2">
                                <div class="form-check-inline">
                                    <label class="form-check-label">
                                        <input type="radio" class="form-check-input" name="is_kredit" value="0" checked>Cash
                                    </label>
                                </div>
                                <div class="form-check-inline">
                                    <label class="form-check-label">
                                    <input type="radio" class="form-check-input" name="is_kredit" value="1">Kredit
                                    </label>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr class="nominal-error" hidden="">
                        <td class="text-danger nominal-min">Nominal bayar kurang</td>
                    </tr>
                    <tr class="detail-kredit" hidden="">
                        <td>
                            <div class="col-12 payment-1 mt-1">
                                <h5>Detail Kredit</h5>
                                <table class="table-payment-1">
                                    <tr class="total-plafon" style="display:none">
                                        <td class="text-left">
                                        <span class="plafon-td">plafon</span>
                                        <a href="#" class="ubah-plafon-td">Ubah plafon</a>
                                        <a href="#" class="simpan-plafon-td" hidden="">Simpan</a>
                                        </td>
                                        <td class="text-right d-flex justify-content-end align-items-center pt-2">
                                            <span>Rp</span>
                                            <input type="number" name="plafon" class="form-control plafon-input mr-2" min="0" max="100" name="plafon" value="0" hidden="" onkeyup="fillNilaiPlafon(event, this)">
                                            <span class="nilai-plafon-td mr-1">0</span>
                                        </td>
                                    </tr>
                                    <tr class="sisa-plafon" style="display:none">
                                        <td class="text-left">Sisa Plafon</td>
                                        <td class="text-right">
                                            <input type="text" onchange="sisaplafonChange(event)" class="form-control invisible-input mr-2" disabled>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-left">DP</td>
                                        <td class="text-right">
                                            <input type="text" name="total_dp" class="form-control mr-2" min="0" max="100" value="0">
                                            <span></span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-left">Tenor</td>
                                        <td class="text-right">
                                            <div class="input-group">
                                                <input type="text" name="tenor" class="form-control" aria-label="tenor" required>
                                                <div class="input-group-append">
                                                    <select name="tenor_unit" id="" class="form-control" required>
                                                        <option value="minggu" selected>Minggu</option>
                                                        <option value="bulan">Bulan</option>
                                                        <option value="tahun">Tahun</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr class="tenor-unit-detail">
                                        <td class="text-left">Cicilan Per <span class="tenor-unit-span">Hari</span></td>
                                        <td class="text-right cicilan-amount d-flex justify-content-end align-items-center pt-2">
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </td>
                    </tr>
                    <tr class="plafon-error" hidden="">
                        <td class="text-danger nominal-min">Plafon kurang</td>
                    </tr>
                    <tr>
                        <td class="text-right">
                            <button class="btn btn-bayar" type="button">
                                Bayar
                            </button>
                        </td>
                    </tr>
                </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</form>
@endsection
@section('script')
<script src="{{ asset('plugins/js/quagga.min.js') }}"></script>
<script src="{{ asset('js/transaction/script.js') }}"></script>
<script type="text/javascript">


// doc ready
$(document).ready(function () {
    @if ($message = Session::get('transaction_success'))
      $('#successModal').modal('show');
    @endif

    $(document).on('click', '.btn-pilih', function(e){
      e.preventDefault();
      var kode_barang = $(this).prev().prev().children().first().text();
      $.ajax({
        url: "{{ url('/transaction/product') }}/" + kode_barang,
        method: "GET",
        success:function(response){
          var check = $('.kode-barang-td:contains('+ response.product.kode_barang +')').length;
          if(check == 0){
            tambahData(response.product, response.status);
          }else{
            swal(
                "",
                "Barang telah ditambahkan",
                "error"
            );
          }
        }
      });
    });

    $("[name='total_dp']").on("change", changeTotalDP)
    $("[name='total_dp']").on("keyup", changeTotalDP)

    $("[name='tenor']").on("change", tenorChange)
    $("[name='tenor']").on("keyup", tenorChange)
    $("[name='tenor_unit']").on("change", (e)=>{
        $(".tenor-unit-span").text(e.target.value);
        tenorChange();
    })



    $("[name='is_kredit']").on("change", e =>{
        e.preventDefault()


        const target = $(e.target);

        // var bayar = parseInt($('.bayar-input').val());

        if(target.val() == 1){
            if(!$("[name='jumlah_barang[]']").length || !$("[name='nik_customer']").val()){
                toastr.error("Lengkapi data barang & data customer terlebih dahulu");
                changePaymentMethod('cash');
                return;
            }

            var total = parseInt($('.nilai-total2-td').val());
            $(".total-plafon").slideDown();
            $(".sisa-plafon").slideDown();
            $(".detail-kredit").prop("hidden", false)

            $('.bayar-input').val(total).parent().slideUp();
        }else{
            $(".total-plafon").slideUp();
            $(".sisa-plafon").slideUp();
            $(".detail-kredit").prop("hidden", true)

            $('.bayar-input').val(0).parent().slideDown();
        }

    })

    const input = $(".nik-customer");
    actOnEndtyping(input,(e)=>{
        const val = input.val();
        if(!val){
            return;
        }

        editCustomerState("nik", val);
        input.parent().find(".nik-msg").html("<small>loading...</small>");

        getCustomerByNik(input.val(),()=>{
            input.parent().find(".nik-msg").html("");
        });
    })

    $(document).on("click", ".btn-search", function (e) {
        e.preventDefault();

        // if .tipe-customer is empty then show error
        if(!$(".tipe-customer").val()){
            $(".tipe-customer").parents("tr").css("border", "1px solid red");
            setTimeout(() => {
                $(".tipe-customer").parents("tr").css("border", "1px solid #ced4da");
            }, 3000);
        }else{
            $("#tableModal").modal("show");
        }

    })

    $(document).on('click', '.btn-scan', function(){
      $('#area-scan').prop('hidden', false);
      $('#btn-scan-action').prop('hidden', true);
      $('.barcode-result').prop('hidden', true);
      $('.barcode-result-text').html('');
      $('.kode_barang_error').prop('hidden', true);
      startScan();
    });

    $(document).on('click', '.btn-repeat', function(){
      $('#area-scan').prop('hidden', false);
      $('#btn-scan-action').prop('hidden', true);
      $('.barcode-result').prop('hidden', true);
      $('.barcode-result-text').html('');
      $('.kode_barang_error').prop('hidden', true);
      startScan();
    });

    $(document).on('click', '.btn-continue', function(e){
      e.stopPropagation();
      var kode_barang = $('.barcode-result-text').text();
      $.ajax({
        url: "{{ url('/transaction/product/check') }}/" + kode_barang,
        method: "GET",
        success:function(response){
          var check = $('.kode-barang-td:contains('+ response.product.kode_barang +')').length;
          if(response.check == 'tersedia'){
            if(check == 0){
                tambahData(response.product, response.status);
              $('.close-btn').click();
            }else{
              swal(
                  "",
                  "Barang telah ditambahkan",
                  "error"
              );
            }
          }else{
            $('.kode_barang_error').prop('hidden', false);
          }
        }
      });
    });

    $(document).on('click', '.btn-bayar', function(){
      var total = parseInt($('.nilai-total2-td').val());
      var bayar = parseInt($('.bayar-input').val());
      var check_barang = parseInt($('.jumlah_barang_text').length);
      var is_kredit = isKredit();
      var totalDp = getNumberFromString(`${$("[name='total_dp']").val() || 0}`)

      if(is_kredit){
        var plafon = parseInt(customer?.plafon || getNumberFromString(`${$(".total-plafon input").val() || 0}`));
        var sisa_plafon = getNumberFromString(`${$(".sisa-plafon input").val() || 0}`);

        if(total > sisa_plafon){
            $('.plafon-error').prop('hidden', false);
            swal(
                "",
                "Plafon tidak mencukupi",
                "error"
            );
            return;
        }
      }

      if(bayar >= total){
        $('.nominal-error').prop('hidden', true);
        if(check_barang != 0){
          if($('.diskon-input').attr('hidden') != 'hidden'){
            $('.diskon-input').addClass('is-invalid');
          }else if(is_kredit && total == totalDp){
            swal("Total Harga sama dengan total Dp, apabila dilanjutkan akan dianggap sebagai transaksi Cash", {
                buttons: {
                    cancel: "Ok!",
                    catch: {
                        text: "Atur Ulang Kredit",
                        value: "catch",
                    },
                },
                })
                .then((value) => {
                switch (value) {

                    case "catch":
                        console.log('atur kredit!')
                    break;

                    default:
                        changePaymentMethod('cash');
                        $("[name='is_kredit']").trigger("change");
                        const totalDp = $("[name='total_dp']");
                        totalDp.val(getNumberFromString(`${totalDp.val() || 0}`))
                        setTimeout(() => $('#transaction_form').submit(), 200);
                    }
                });
            }else{
                const totalDp = $("[name='total_dp']");
                totalDp.val(getNumberFromString(`${totalDp.val() || 0}`))
                setTimeout(() => $('#transaction_form').submit(), 200);
            }
        }else{
          swal(
              "",
              "Pesanan Kosong",
              "error"
          );
        }
      }else{
        if(isNaN(bayar)) {
          $('.bayar-input').valid();
        }else{
          $('.nominal-error').prop('hidden', false);
        }

        if(check_barang == 0){
          swal(
              "",
              "Pesanan Kosong",
              "error"
          );
        }
      }
    });

})
</script>
@endsection
