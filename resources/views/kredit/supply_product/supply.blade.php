@extends('templates/main')
@section('css')
    <link rel="stylesheet" href="{{ asset('css/manage_product/supply_product/supply/style.css') }}">
@endsection
@section('content')
    <div class="row page-title-header">
        <div class="col-12">
            <div class="page-header d-flex justify-content-between align-items-center">
                <h4 class="page-title">Riwayat Pasok</h4>
                <div class="d-flex justify-content-start">
                    <a href="{{ url('/supply/statistics') }}"
                        class="btn btn-icons btn-inverse-primary btn-filter shadow-sm ml-2">
                        <i class="mdi mdi-poll"></i>
                    </a>
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
                    <a href="{{ url('/supply/new') }}" class="btn btn-icons btn-inverse-primary btn-new ml-2">
                        <i class="mdi mdi-plus"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 grid-margin">
            <div class="card card-noborder b-radius">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            @foreach ($chunk_by_date as $date => $supplies)
                                <div class="mb-2">
                                    <span class="d-flex mb-2 mt-1 txt-light">{{ date('d M, Y', strtotime($date)) }}</span>
                                    @foreach ($supplies as $key => $supply)
                                        @php
                                            $supply_products = $supply->supply_product;
                                            $formatted_date = date('d M, Y - H:i', strtotime($supply->created_at));

                                            $idkey = str_replace("-", "", $date) . $key;
                                        @endphp
                                        <div class="accordion" id="accordionSupply{{ $idkey }}">
                                            <div class="card">
                                            <div class="card-header p-2 border-bottom-0" id="headingSupply{{ $idkey }}">
                                                <button class="btn btn-block text-left mb-0 pb-0" type="button" data-toggle="collapse" data-target="#collapseSupply{{ $idkey }}" aria-expanded="true" aria-controls="collapseSupply{{ $idkey }}">
                                                    <span class="btn-link font-weight-bold"> #{{ $supply->kode_supply }} </span>
                                                    <span class="ml-2 text-dark text-decoration-none" style="font-size:medium">→ Rp.{{ number_format($supply->total_harga, 2, ',', '.') }} </span>
                                                    {!! $supply->is_import ? '<span class="badge badge-pill badge-success p-1 text-white">Import Excel</span>' : '' !!}
                                                </button>
                                                <span class="ml-3" style="font-size: 11px"> {{ str_replace("-", "pada", $formatted_date) }}</span>
                                            </div>

                                            <div id="collapseSupply{{ $idkey }}" class="collapse" aria-labelledby="headingSupply{{ $idkey }}" data-parent="#accordionSupply{{ $idkey }}">
                                                <div class="card-body p-1">
                                                    <div class="table-responsive">
                                                        <table class="table table-custom">
                                                            <tr>
                                                                <th>Nama Barang</th>
                                                                <th>Kode Barang</th>
                                                                <th>Jumlah</th>
                                                                <th>Harga Beli</th>
                                                                <th>Total</th>
                                                                <th>Pemasok</th>
                                                            </tr>
                                                            @foreach ($supply_products as $sp)
                                                                <tr>
                                                                    <td class="td-1">
                                                                        <span class="d-block font-weight-bold big-font">{{ $sp->product->nama_barang }}</span>
                                                                    </td>
                                                                    <td class="td-2 font-weight-bold">{{ $sp->product->kode_barang }}
                                                                    </td>
                                                                    <td class="td-3 font-weight-bold">
                                                                        {{ $sp->jumlah }}
                                                                        <span
                                                                            class="badge badge-secondary badge-sm">{{ $sp->product->satuan->name }}</span>
                                                                    </td>
                                                                    <td class="font-weight-bold td-4"><input type="text" name="harga"
                                                                            value="{{ $sp->harga_beli }}" hidden=""><span
                                                                            class="ammount-box bg-green"><i
                                                                                class="mdi mdi-coin"></i></span>Rp.
                                                                        {{ number_format($sp->harga_beli, 2, ',', '.') }}</td>
                                                                    <td class="total-field font-weight-bold text-success">{{ number_format($sp->total_harga_beli, 2, ',', '.') }}</td>
                                                                    <td class="font-weight-bold">{{ $sp->supply->supplier->name }}</td>
                                                                </tr>
                                                            @endforeach
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script src="{{ asset('js/manage_product/supply_product/supply/script.js') }}"></script>
    <script type="text/javascript">
        @if ($message = Session::get('create_success'))
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
    </script>
@endsection
