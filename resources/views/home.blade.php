<!DOCTYPE html>
<html lang="en" class="light-style layout-menu-fixed" dir="ltr" data-theme="theme-default">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <title>Dashboard | Inventaris Barang</title>

    <link rel="icon" type="image/x-icon" href="{{ asset('assets/img/favicon/favicon.ico') }}" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/boxicons.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/core.css') }}" class="template-customizer-core-css" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/theme-default.css') }}" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="{{ asset('assets/css/demo.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />

    <script src="{{ asset('assets/vendor/js/helpers.js') }}"></script>
    <script src="{{ asset('assets/js/config.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            @include('layouts.components.sidebar')

            <div class="layout-page">
                @include('layouts.components.navbar')

                <div class="content-wrapper">
                    <div class="container-xxl flex-grow-1 container-p-y">
                        
                        <div class="row">
                            <div class="col-lg-12 mb-4">
                                <div class="card">
                                    <div class="d-flex align-items-end row">
                                        <div class="col-sm-7">
                                            <div class="card-body">
                                                <h5 class="card-title text-primary">Selamat Datang, {{ auth()->user()->name }}! 🎉</h5>
                                                <p class="mb-4">Role Anda: <span class="badge bg-label-info text-uppercase">{{ auth()->user()->role }}</span></p>
                                                <a href="{{ route('barang.index') }}" class="btn btn-sm btn-outline-primary">Lihat Data Barang</a>
                                            </div>
                                        </div>
                                        <div class="col-sm-5 text-center text-sm-left">
                                            <div class="card-body pb-0 px-0 px-md-4">
                                                <img src="{{ asset('assets/img/illustrations/man-with-laptop-light.png') }}" height="140" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-3 col-md-6 col-sm-6 mb-4 text-center">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="avatar mx-auto mb-2">
                                            <span class="badge bg-label-primary p-2"><i class="bx bx-package fs-3"></i></span>
                                        </div>
                                        <span class="d-block mb-1">Stok Barang</span>
                                        <h4 class="card-title mb-2">{{ \App\Models\Barang::count() }}</h4>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6 col-sm-6 mb-4 text-center">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="avatar mx-auto mb-2">
                                            <span class="badge bg-label-success p-2"><i class="bx bx-down-arrow-circle fs-3"></i></span>
                                        </div>
                                        <span class="d-block mb-1">Barang Masuk</span>
                                        <h4 class="card-title mb-2">{{ \App\Models\BarangMasuk::count() }}</h4>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6 col-sm-6 mb-4 text-center">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="avatar mx-auto mb-2">
                                            <span class="badge bg-label-danger p-2"><i class="bx bx-up-arrow-circle fs-3"></i></span>
                                        </div>
                                        <span class="d-block mb-1">Barang Keluar</span>
                                        <h4 class="card-title mb-2">{{ \App\Models\BarangKeluar::count() }}</h4>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6 col-sm-6 mb-4 text-center">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="avatar mx-auto mb-2">
                                            <span class="badge bg-label-warning p-2"><i class="bx bx-user fs-3"></i></span>
                                        </div>
                                        <span class="d-block mb-1">Peminjam</span>
                                        <h4 class="card-title mb-2">{{ \App\Models\BarangPeminjam::count() }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="{{ auth()->user()->role == 'admin' ? 'col-md-6 col-lg-8' : 'col-12' }} mb-4">
                                <div class="card h-100">
                                    <h5 class="card-header">Log Aktivitas Terbaru</h5>
                                    <div class="table-responsive text-nowrap">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>Nama Barang</th>
                                                    <th>Stok Sekarang</th>
                                                    <th>Update</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php
                                                    $barangs = \App\Models\Barang::latest('updated_at')->take(5)->get();
                                                @endphp
                                                @forelse($barangs as $item)
                                                <tr>
                                                    <td>
                                                        <strong>{{ $item->name_barang ?? $item->nama_barang ?? 'Barang Tanpa Nama' }}</strong>
                                                    </td>
                                                    <td>
                                                        @php $stokReal = $item->stock ?? $item->stok ?? 0; @endphp
                                                        @if($stokReal > 0)
                                                            <span class="badge bg-label-success">Tersedia: {{ $stokReal }}</span>
                                                        @else
                                                            <span class="badge bg-label-danger">KOSONG</span>
                                                        @endif
                                                    </td>
                                                    <td class="small">{{ $item->updated_at->diffForHumans() }}</td>
                                                </tr>
                                                @empty
                                                <tr>
                                                    <td colspan="3" class="text-center">Data kosong di database.</td>
                                                </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            @if(auth()->user()->role == 'admin')
                            <div class="col-md-6 col-lg-4 mb-4">
                                <div class="card h-100 bg-primary text-white">
                                    <div class="card-body text-center d-flex flex-column justify-content-center align-items-center">
                                        <i class="bx bx-user-plus fs-1 mb-3"></i>
                                        <h5 class="card-title text-white">Atur Petugas</h5>
                                        <p class="card-text mb-3">Kelola data user dan hak akses petugas.</p>
                                        <a href="{{ route('petugas.index') }}" class="btn btn-sm btn-white bg-white text-primary fw-bold">Buka Menu Petugas</a>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>

                        <div class="row">
                            <div class="col-12 mb-4">
                                <div class="card">
                                    <h5 class="card-header">Visualisasi Data Inventaris</h5>
                                    <div class="card-body">
                                        <div style="height: 200px;">
                                            <canvas id="myChart"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    @include('layouts.components.footer')
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('assets/vendor/libs/jquery/jquery.js') }}"></script>
    <script src="{{ asset('assets/vendor/js/bootstrap.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>
    <script src="{{ asset('assets/vendor/js/menu.js') }}"></script>
    <script src="{{ asset('assets/js/main.js') }}"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('myChart').getContext('2d');
            
            // Membuat efek gradien warna (seperti gunung)
            const gradient = ctx.createLinearGradient(0, 0, 0, 200);
            gradient.addColorStop(0, 'rgba(105, 108, 255, 0.4)');
            gradient.addColorStop(1, 'rgba(105, 108, 255, 0)');

            new Chart(ctx, {
                type: 'line', // Mengubah bar menjadi line area
                data: {
                    labels: ['Total Barang', 'Barang Masuk', 'Barang Keluar', 'Peminjam'],
                    datasets: [{
                        label: 'Statistik Inventaris',
                        data: [
                            {{ \App\Models\Barang::count() }}, 
                            {{ \App\Models\BarangMasuk::count() }}, 
                            {{ \App\Models\BarangKeluar::count() }}, 
                            {{ \App\Models\BarangPeminjam::count() }}
                        ],
                        borderColor: '#696cff',
                        backgroundColor: gradient,
                        fill: true, // Mengisi area bawah garis
                        tension: 0.4, // Membuat garis melengkung (smooth)
                        pointBackgroundColor: '#696cff',
                        pointBorderColor: '#fff',
                        pointHoverRadius: 5,
                        pointRadius: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false } // Sembunyikan label atas agar lebih bersih
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { display: false }
                        },
                        x: {
                            grid: { display: false }
                        }
                    }
                }
            });
        });
    </script>
</body>
</html>