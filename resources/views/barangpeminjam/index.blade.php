<!DOCTYPE html>

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

    <title>Data Peminjam Barang | Sneat - Admin</title>

    <meta name="description" content="" />

    <link rel="icon" type="image/x-icon" href="../assets/img/favicon/favicon.ico" />

    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
      rel="stylesheet"
    />

    <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/boxicons.css') }}" />

    <link rel="stylesheet" href="{{ asset('assets/vendor/css/core.css') }}" class="template-customizer-core-css" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/theme-default.css') }}" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="{{ asset('assets/css/demo.css') }}" />

    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/apex-charts/apex-charts.css') }}" />

    <script src="{{ asset('assets/vendor/js/helpers.js') }}"></script>
    <script src="{{ asset('assets/js/config.js') }}"></script>
  </head>

  <body>
    <div class="layout-wrapper layout-content-navbar">
      <div class="layout-container">
        @include('layouts.components.sidebar')
        <div class="layout-page">
          @include('layouts.components.navbar')
          <div class="content-wrapper">
            <div class="container-xxl flex-grow-1 container-p-y">
              <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Tables /</span> Data Peminjam Barang</h4>
              
              <a href="{{ route('barangpeminjam.create') }}" class="btn btn-primary my-4">
                <i class="bx bx-folder-plus"></i> Tambah Data
              </a>
              <i class="mb-3">
    <a href="{{ route('barangmasuk.exportExcel') }}" class="btn btn-success">export to Excel</a>
    <a href="{{ route('barangmasuk.exportPdf') }}" class="btn btn-danger">export to PDF</a>
</i>
              <div class="card">
                <h5 class="card-header">Daftar Peminjam Barang</h5>
                
                <div class="table text-nowrap">
                  <table class="table table-hover">
                    <thead>
                      <tr>
                        <th>No</th>
                        <th>Nama Peminjam</th>
                        <th>Nama Barang</th>
                        <th>Merek</th>
                        <th>Jumlah</th>
                        <th>Keterangan</th>
                        <th>Tgl Pinjam</th>
                        <th>Tgl Kembali</th>
                        <th>Status</th>
                        <th>Actions</th>
                      </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                      @php $no = 1; @endphp
                      @foreach($peminjam as $data)
                      <tr>
                        <td><strong>{{ $no++ }}</strong></td>
                        <td>{{ $data->nama_peminjam }}</td>   
                        <td>{{ $data->barang->name_barang }}</td>
                        <td>{{ $data->merek }}</td>
                        <td>{{ $data->jumlah }}</td>
                        <td>{{ $data->keterangan }}</td>
                        <td>{{ $data->tanggal_peminjaman }}</td>
                        <td>{{ $data->tanggal_pengembalian }}</td>
                        <td>
                          {{-- Custom Badge Status --}}
                          @if($data->status == 'dipinjam' || $data->status == 'proses')
                            <span class="badge bg-label-warning">{{ $data->status }}</span>
                          @elseif($data->status == 'dikembalikan' || $data->status == 'selesai')
                            <span class="badge bg-label-success">{{ $data->status }}</span>
                          @else
                            <span class="badge bg-label-secondary">{{ $data->status }}</span>
                          @endif
                        </td>
                        <td>
                          <div class="dropdown">
                            <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                              <i class="bx bx-dots-vertical-rounded"></i>
                            </button>
                            <div class="dropdown-menu">
                              <a class="dropdown-item" href="{{ route('barangpeminjam.show',$data->id) }}">
                                <i class="bx bx-show-alt me-1"></i> Show
                              </a>
                              <a class="dropdown-item" href="{{ route('barangpeminjam.edit',$data->id) }}">
                                <i class="bx bx-edit-alt me-1"></i> Edit
                              </a>
                              <form action="{{ route('barangpeminjam.destroy', $data->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="dropdown-item" onclick="return confirm('Apakah anda yakin menghapus data ini?')">
                                  <i class="bx bx-trash me-1"></i> Delete
                                </button>
                              </form>
                            </div>
                          </div>
                        </td>
                      </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
              </div>
              </div>
            @include('layouts.components.footer')

            <div class="content-backdrop fade"></div>
          </div>
          </div>
        </div>

      <div class="layout-overlay layout-menu-toggle"></div>
    </div>
    <script src="{{ asset('assets/vendor/libs/jquery/jquery.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/popper/popper.js') }}"></script>
    <script src="{{ asset('assets/vendor/js/bootstrap.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>
    <script src="{{ asset('assets/vendor/js/menu.js') }}"></script>

    <script src="{{ asset('assets/vendor/libs/apex-charts/apexcharts.js') }}"></script>

    <script src="{{ asset('assets/js/main.js') }}"></script>

    <script src="{{ asset('assets/js/dashboards-analytics.js') }}"></script>

    <script async defer src="https://buttons.github.io/buttons.js"></script>
  </body>
</html>