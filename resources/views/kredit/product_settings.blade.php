@extends('templates/main')
@section('css')
    <link rel="stylesheet" href="{{ asset('css/transaction/style.css') }}">
@endsection
@section('content')
    <div class="row page-title-header">
        <div class="col-12">
            <div class="page-header d-flex justify-content-start align-items-center">
                <h4 class="page-title">Pengaturan Barang</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-4 col-md-4 col-sm-12">
            <div class="card card-noborder b-radius">
                <div class="card-body">
                    <div class="row">
                        <strong>Satuan Barang</strong>
                    </div>
                    <div class="row">
                        <div class="col-12 mt-2">
                            <table class="table-payment-3">
                                <tr>
                                    <td>
                                        <div class="input-group">
                                            <div class="input-group-prepend btn-cancel d-none">
                                                <button type="button"
                                                    style="background-color: transparent;border: none;color:red"
                                                    onclick="btn_action(event)" data-action="cancel">x</button>
                                            </div>
                                            <input type="text" class="form-control unit-input" name="unit"
                                                placeholder="Masukkan satuan barang">
                                            <div class="input-group-prepend">
                                                <button class="btn btn-bayar btn-saveunit" type="button"
                                                    onclick="btn_action(event)">save</button>
                                            </div>
                                            <div class="input-group-prepend btn-delete d-none">
                                                <button class="btn btn-danger" style="font-weight: bold" type="button"
                                                    onclick="btn_action(event)" data-action="delete">hapus</button>
                                            </div>

                                        </div>
                                    </td>
                                </tr>
                                <tr class="nominal-error" hidden="">
                                    <td class="text-danger nominal-min">Nominal unit kurang</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="row">
                        <table class="table">
                            <tbody id="table_data">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-12">
            <div class="card card-noborder b-radius">
                <div class="card-body">
                    <div class="row">
                        <strong>PPn Barang</strong>
                    </div>
                    <div class="row">
                        <div class="col-12 mt-2">
                            <table class="table-payment-3">
                                <tr>
                                    <td>
                                        <form action="{{ url('/product/ppn') }}" method="post">
                                            @csrf
                                            <div class="input-group">
                                                <input type="number" min="0" class="form-control ppn-input" name="ppn"
                                                    placeholder="Masukkan ppn barang"
                                                    value="{{ old('ppn') ?? \GS::get('ppn') }}">
                                                <div class="input-group-prepend">
                                                    <button class="btn btn-bayar" type="submit">save</button>
                                                </div>
                                            </div>
                                        </form>
                                    </td>
                                </tr>
                                @if ($errors->has('ppn'))
                                    <tr class="ppn-error">
                                        <td class="text-danger py-0" style="font-size: 13px">{{ $errors->first('ppn') }}
                                        </td>
                                    </tr>
                                @endif
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
    <script src="{{ asset('js/manage_product/product/settings.js') }}"></script>
    <script>
        var urlSetProduct = "{{ route('product.unit.set') }}";
        var urlGetProduct = "{{ route('product.unit.get') }}";
    </script>
@endsection
