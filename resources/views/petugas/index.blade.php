<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Petugas - Inventaris</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-light">

<div class="container py-5">
    <div class="row">
        <div class="col-12 mb-4 d-flex justify-content-between align-items-center">
            <h2><i class="fas fa-users-cog me-2"></i> Manajemen Petugas</h2>
            <a href="{{ route('barang.index') }}" class="btn btn-secondary btn-sm">Kembali ke Data Barang</a>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(auth()->user()->role == 'admin')
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white fw-bold">
                    Tambah Petugas Baru
                </div>
                <div class="card-body">
                    <form action="{{ route('petugas.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" name="name" class="form-control" placeholder="Input nama..." required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" placeholder="email@contoh.com" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" placeholder="Minimal 8 karakter" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Role</label>
                            <select name="role" class="form-select">
                                <option value="petugas">Petugas</option>
                                <option value="admin">Admin</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-save me-1"></i> Simpan Petugas
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @endif

        <div class="{{ auth()->user()->role == 'admin' ? 'col-md-8' : 'col-12' }}">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white fw-bold">
                    Daftar Petugas Aktif
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    @if(auth()->user()->role == 'admin')
                                        <th class="text-center">Aksi</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $user)
                                <tr>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        <span class="badge {{ $user->role == 'admin' ? 'bg-danger' : 'bg-info' }}">
                                            {{ strtoupper($user->role) }}
                                        </span>
                                    </td>
                                    @if(auth()->user()->role == 'admin')
                                    <td class="text-center">
                                        @if($user->id !== auth()->id())
                                            <form action="{{ route('petugas.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus petugas ini?')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        @else
                                            <small class="text-muted">Anda</small>
                                        @endif
                                    </td>
                                    @endif
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>