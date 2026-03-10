<!DOCTYPE html>

<!-- =========================================================
* Sneat - Bootstrap 5 HTML Admin Template - Pro | v1.0.0
==============================================================

* Product Page: https://themeselection.com/products/sneat-bootstrap-html-admin-template/
* Created by: ThemeSelection
* License: You must have a valid license purchased in order to legally use the theme for your project.
* Copyright ThemeSelection (https://themeselection.com)

=========================================================
 -->
<!-- beautify ignore:start -->
<html
  lang="en"
  class="light-style layout-menu-fixed"
  dir="ltr"
  data-theme="theme-default"
  data-assets-path="../assets/"
  data-template="vertical-menu-template-free"
>
  <head>
    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"
    />

    <title>Dashboard - Analytics | Sneat - Bootstrap 5 HTML Admin Template - Pro</title>

    <meta name="description" content="" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="../assets/img/favicon/favicon.ico" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
      rel="stylesheet"
    />

    <!-- Icons. Uncomment required icon fonts -->
    <link rel="stylesheet" href="{{asset('assets/vendor/fonts/boxicons.css') }}" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/core.css') }}" class="template-customizer-core-css" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/theme-default.css') }}" class="template-customizer-theme-css" />

    <link rel="stylesheet" href="{{asset('assets/css/demo.css') }}" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="{{asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />

    <link rel="stylesheet" href="{{asset('assets/vendor/libs/apex-charts/apex-charts.css') }}" />

    <!-- Page CSS -->

    <!-- Helpers -->
    <script src="{{asset('assets/vendor/js/helpers.js')}}"></script>

    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="{{asset('assets/js/config.js')}}"></script>
  </head>
  <body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
      <div class="layout-container">
        <!-- Menu -->
        @include('layouts.components.sidebar')
        <!-- / Menu -->
        <!-- Layout container -->
        <div class="layout-page">
          <!-- Navbar -->
          @include('layouts.components.navbar')
          <!-- / Navbar -->
          <!-- Content wrapper -->
          <div class="content-wrapper">
            <!-- Content -->
            <div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4">Laporan Inventaris</h4>

    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('laporan.index') }}" method="GET" class="row align-items-end">
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Dari Tanggal</label>
                    <input type="date" name="dari" value="{{ $dari }}" class="form-control">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Sampai Tanggal</label>
                    <input type="date" name="sampai" value="{{ $sampai }}" class="form-control">
                </div>
                <div class="col-md-6 mt-3 mt-md-0">
                    <button type="submit" class="btn btn-primary me-2">
                        <i class="bx bx-search fs-4 lh-0"></i> Filter
                    </button>
                    <a href="{{ route('barang.export') }}" class="btn btn-success my-4">
                     <i class="bx bx-file-export"></i> Export to Excel
                    </a>
                    <a href="{{ route('barang.export.pdf') }}" class="btn btn-danger my-4">
                    <i class="bx bx-file-export"></i> Export to PDF
                    </a>
                    <button type="button" onclick="window.print()" class="btn btn-outline-secondary">
                        <i class="bx bx-printer fs-4 lh-0"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card shadow-none border-start border-info border-5">
                <div class="card-body">
                    <span class="d-block mb-1 text-muted text-uppercase fw-bold small">Total Barang Masuk</span>
                    <h4 class="card-title mb-2">{{ \App\Models\BarangMasuk::count() }}</h4>
                    <small class="text-muted">Periode ini</small>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card shadow-none border-start border-primary border-5">
                <div class="card-body">
                    <span class="d-block mb-1 text-muted text-uppercase fw-bold small">Total Barang Keluar</span>
                    <h4 class="card-title mb-2">{{ \App\Models\BarangKeluar::count() }}</h4>
                    <small class="text-muted">Unit barang</small>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card shadow-none border-start border-success border-5">
                <div class="card-body">
                    <span class="d-block mb-1 text-muted text-uppercase fw-bold small">Total Transaksi Pinjam</span>
                    <h4 class="card-title mb-2">{{ \App\Models\BarangPeminjam::count() }}</h4>
                    <small class="text-muted">Order paid</small>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        {{-- Sales By Category --}}
        <div class="col-lg-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">Performa Kategori</h5>
                </div>
                <div class="card-body">
                    @foreach($rincian as $cat)
                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-1">
                                <span class="fw-medium">{{ $cat->name }}</span>
                                <span class="fw-bold">Rp {{ number_format($cat->total, 0, ',', '.') }}</span>
                            </div>
                            <div class="progress" style="height: 6px;">
                                <div class="progress-bar" role="progressbar"
                                     style="width: {{ ($cat->total / ($totalPeminjam ->total_revenue ?: 1)) * 100 }}%">
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="col-md-8 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="m-0">Rincian Transaksi</h5>
                </div>
                <div class="table-responsive text-nowrap">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Order ID</th>
                                <th>Tanggal</th>
                                <th>Barang</th>
                                <th class="text-end">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($rincian as $index => $item)
                            <tr>
                                <td><span class="fw-bold">#{{ $item->id }}</span></td>
                                <td>{{ \Carbon\Carbon::parse($item->tanggal_barang_keluar)->format('d/m/Y') }}</td>
                                <td>{{ $item->barang->name_barang }}</td>
                                <td class="text-end fw-semibold">{{ $item->jumlah }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-5 text-muted">
                                    Tidak ada data penjualan pada periode ini.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
 @include('layouts.components.footer')
    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->
    <script src="{{asset('assets/vendor/libs/jquery/jquery.js')}}"></script>
    <script src="{{asset('assets/vendor/libs/popper/popper.js')}}"></script>
    <script src="{{asset('assets/vendor/js/bootstrap.js')}}"></script>
    <script src="{{asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js')}}"></script>

    <script src="{{asset('assets/vendor/js/menu.js')}}"></script>
    <!-- endbuild -->

    <!-- Vendors JS -->
    <script src="{{asset('assets/vendor/libs/apex-charts/apexcharts.js')}}"></script>

    <!-- Main JS -->
    <script src="{{asset('assets/js/main.js')}}"></script>

    <!-- Page JS -->
    <script src="{{asset('assets/js/dashboards-analytics.js')}}"></script>

    <!-- Place this tag in your head or just before your close body tag. -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
  </body>
</html>