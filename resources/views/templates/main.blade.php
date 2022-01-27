<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="CSRF_TOKEN" content="{{ csrf_token() }}">
    <title>{{ env('APP_NAME') }}</title>

    <!-- CSS -->
    <link rel="stylesheet" href="{{ asset('assets/vendors/iconfonts/mdi/css/materialdesignicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/iconfonts/ionicons/css/ionicons.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/iconfonts/typicons/src/font/typicons.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/iconfonts/flag-icon-css/css/flag-icon.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/css/vendor.bundle.base.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/css/vendor.bundle.addons.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/shared/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/demo_1/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/main/style.css') }}">
    <link rel="shortcut icon" href="{{ asset('icons/favicon.png') }}" />
    <link href="https://fonts.googleapis.com/css2?family=Rubik:wght@300&display=swap" rel="stylesheet">
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">
    @yield('css')
    <!-- End-CSS -->

</head>

<body>
    <div class="container-scroller">
        <!-- TopNav -->
        <nav class="navbar default-layout col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
            <div class="text-center navbar-brand-wrapper d-flex align-items-top justify-content-center">
                <a class="navbar-brand brand-logo" href="{{ url('/dashboard') }}">
                    <img src="{{ asset('icons/logo.png') }}" alt="logo" /> </a>
                <a class="navbar-brand brand-logo-mini" href="{{ url('/dashboard') }}">
                    <img src="{{ asset('icons/logo-mini.png') }}" alt="logo" /> </a>
            </div>
            <div class="navbar-menu-wrapper d-flex align-items-center">
                <form class="search-form d-none d-md-block" action="#">
                    <div class="form-group">
                        <input type="search" class="form-control" name="search_page" placeholder="Cari Halaman">
                    </div>
                </form>
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item dropdown">
                        @php
                            $fn = function ($supply) {
                                $supply
                                    // ->where('show_stock', 1)
                                    ->havingRaw('SUM(jumlah) < ?', [10]);
                            };
                            
                            $cek_supply_system = \App\Supply_system::first();
                            $notifications = \App\Product::whereHas('supply', $fn)->get();
                            $jumlah_notif = $notifications->count();
                            $notification = \App\Product::whereHas('supply', $fn)
                                ->take(3)
                                ->get();
                        @endphp
                        <a class="nav-link count-indicator" id="notificationDropdown" href="#" data-toggle="dropdown">
                            <i class="mdi mdi-bell-outline"></i>
                            @if ($cek_supply_system->status == 1)
                                @if ($jumlah_notif != 0)
                                    <span class="count bg-success">{{ $jumlah_notif }}</span>
                                @endif
                            @endif
                        </a>
                        <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list pb-0"
                            aria-labelledby="notificationDropdown">
                            <div class="dropdown-item py-3 border-bottom">
                                @if ($cek_supply_system->status == 1)
                                    <p class="mb-0 font-weight-medium float-left">Anda Memiliki {{ $jumlah_notif }}
                                        Pemberitahuan</p>
                                @else
                                    <p class="mb-0 font-weight-medium float-left">Anda Memiliki 0 Pemberitahuan</p>
                                @endif
                                <a href="#" role="button" data-toggle="modal" data-target="#notificationModal"><span
                                        class="badge badge-pill badge-primary float-right">Semua</span></a>
                            </div>
                            @if ($cek_supply_system->status == 1)
                                @foreach ($notification as $notif)
                                    @if ($notif->stok != 0)
                                        <a class="dropdown-item preview-item py-3">
                                            <div class="preview-thumbnail">
                                                <i class="mdi mdi-alert m-auto text-warning"></i>
                                            </div>
                                            <div class="preview-item-content">
                                                <h6 class="preview-subject font-weight-normal text-dark mb-1">Barang
                                                    Hampir Habis</h6>
                                                <p class="font-weight-light small-text mb-0"> Stok
                                                    {{ $notif->nama_barang }} tersisa {{ $notif->stok }} </p>
                                            </div>
                                        </a>
                                    @else
                                        <a class="dropdown-item preview-item py-3">
                                            <div class="preview-thumbnail">
                                                <i class="mdi mdi-alert m-auto text-danger"></i>
                                            </div>
                                            <div class="preview-item-content">
                                                <h6 class="preview-subject font-weight-normal text-dark mb-1">Barang
                                                    Telah Habis</h6>
                                                <p class="font-weight-light small-text mb-0"> Stok barang
                                                    {{ $notif->nama_barang }} telah habis</p>
                                            </div>
                                        </a>
                                    @endif
                                @endforeach
                            @endif
                        </div>
                    </li>
                    <li class="nav-item dropdown d-none d-xl-inline-block user-dropdown">
                        <a class="nav-link dropdown-toggle" id="UserDropdown" href="#" data-toggle="dropdown"
                            aria-expanded="false">
                            <img class="img-xs rounded-circle" src="{{ asset('pictures/' . auth()->user()->foto) }}"
                                alt="Profile image"> </a>
                        <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="UserDropdown">
                            <div class="dropdown-header text-center">
                                <img class="img-md rounded-circle"
                                    src="{{ asset('pictures/' . auth()->user()->foto) }}" alt="Profile image">
                                <p class="mb-1 mt-3 font-weight-semibold">{{ auth()->user()->nama }}</p>
                                <p class="font-weight-light text-muted mb-0">{{ auth()->user()->email }}</p>
                            </div>
                            <a href="{{ url('/profile') }}" class="dropdown-item">Profil</a>
                            <a href="{{ url('/logout') }}" class="dropdown-item">Sign Out</a>
                        </div>
                    </li>
                </ul>
                <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button"
                    data-toggle="offcanvas">
                    <span class="mdi mdi-menu"></span>
                </button>
            </div>
        </nav>
        <!-- End-TopNav -->

        <div class="container-fluid page-body-wrapper">
            <!-- SideNav -->
            <nav class="sidebar sidebar-offcanvas" id="sidebar">
                @include('templates.sidebar')
            </nav>
            <!-- End-SideNav -->

            <div class="main-panel">
                <div class="row">
                    <div class="modal fade" id="notificationModal" tabindex="-1" role="dialog"
                        aria-labelledby="notificationModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="notificationModalLabel">Daftar Notifikasi</h5>
                                    <button type="button" class="close close-btn" data-dismiss="modal"
                                        aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-12">
                                            @if ($cek_supply_system->status == 1)
                                                @foreach ($notifications as $notif)
                                                    @if ($notif->stok != 0)
                                                        <div
                                                            class="d-flex justify-content-start align-items-center mb-3">
                                                            <div class="icon-notification">
                                                                <i class="mdi mdi-alert m-auto text-warning"></i>
                                                            </div>
                                                            <div class="text-group ml-3">
                                                                <p class="m-0 title-notification">Barang Hampir Habis
                                                                </p>
                                                                <p class="m-0 description-notification">Stok
                                                                    {{ $notif->nama_barang }} tersisa
                                                                    {{ $notif->stok }}</p>
                                                            </div>
                                                        </div>
                                                    @else
                                                        <div
                                                            class="d-flex justify-content-start align-items-center mb-3">
                                                            <div class="icon-notification">
                                                                <i class="mdi mdi-alert m-auto text-danger"></i>
                                                            </div>
                                                            <div class="text-group ml-3">
                                                                <p class="m-0 title-notification">Barang Telah Habis
                                                                </p>
                                                                <p class="m-0 description-notification">Stok barang
                                                                    {{ $notif->nama_barang }} telah habis</p>
                                                            </div>
                                                        </div>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="content-wrapper" id="content-web-page">
                    @yield('content')
                </div>
                <div class="content-wrapper" id="content-web-search" hidden="">
                    <div class="row">
                        <div class="col-12 text-left">
                            <h3 class="d-block">Cari Halaman</h3>
                            <h5 class="mt-3 d-block"><span class="result-1"></span> <span
                                    class="result-2"></span></h5>
                        </div>
                        <div class="col-12 mt-3">
                            <div class="row" id="page-result-parent">
                            </div>
                        </div>
                    </div>
                </div>
                <footer class="footer" id="footer-content">
                    <div class="container-fluid clearfix">
                        <span class="text-muted d-block text-center text-sm-left d-sm-inline-block">Copyright ©
                            {{ date('Y') }}. All rights reserved.</span>
                        <span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center">Hand-crafted & made
                            with <i class="mdi mdi-heart text-danger"></i>
                        </span>
                    </div>
                </footer>
            </div>
        </div>
    </div>

    <!-- Javascript -->
    <script src="{{ asset('assets/vendors/js/vendor.bundle.base.js') }}"></script>
    <script src="{{ asset('assets/vendors/js/vendor.bundle.addons.js') }}"></script>
    <script src="{{ asset('assets/js/shared/off-canvas.js') }}"></script>
    <script src="{{ asset('assets/js/shared/misc.js') }}"></script>
    <script src="{{ asset('plugins/js/jquery.form-validator.min.js') }}"></script>
    <script src="{{ asset('plugins/js/sweetalert.min.js') }}"></script>
    <script src="{{ asset('plugins/js/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('js/templates/script.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>

    <script type="text/javascript">
        $(document).on('input', 'input[name=search_page]', function() {
            if ($(this).val() != '') {
                $('#content-web-page').prop('hidden', true);
                $('#content-web-search').prop('hidden', false);
                var search_word = $(this).val();
                $.ajax({
                    url: "{{ url('/search') }}/" + search_word,
                    method: "GET",
                    success: function(response) {
                        $('.result-1').html(response.length + ' Hasil Pencarian');
                        $('.result-2').html('dari "' + search_word + '"');
                        var lengthLoop = response.length - 1;
                        var searchResultList = '';
                        for (var i = 0; i <= lengthLoop; i++) {
                            var page_url = "{{ url('/', ':id') }}";
                            page_url = page_url.replace('%3Aid', response[i].page_url);
                            searchResultList += '<a href="' + page_url +
                                '" class="page-result-child mb-4 w-100"><div class="col-12"><div class="card card-noborder b-radius"><div class="card-body"><div class="row"><div class="col-12"><h5 class="d-block page_url">' +
                                response[i].page_name +
                                '</h5><p class="align-items-center d-flex justify-content-start"><span class="icon-in-search mr-2"><i class="mdi mdi-chevron-double-right"></i></span> <span class="breadcrumbs-search page_url">' +
                                response[i].page_title +
                                '</span></p><div class="search-description"><p class="m-0 p-0 page_url">' +
                                response[i].page_content.substring(0, 500) +
                                '...</p></div></div></div></div></div></div></a>';
                        }
                        $('#page-result-parent').html(searchResultList);
                        $('.page_url:contains("' + search_word + '")').each(function() {
                            var regex = new RegExp(search_word, 'gi');
                            $(this).html($(this).text().replace(regex,
                                '<span style="background-color: #607df3;">' +
                                search_word + '</span>'));
                        });
                    }
                });
            } else {
                $('#content-web-page').prop('hidden', false);
                $('#content-web-search').prop('hidden', true);
            }
        });
    </script>
    @yield('script')
    <!-- End-Javascript -->
</body>

</html>
