@extends('layouts.app')

@section('title', 'Profil')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Profil Pengguna</h1>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="row">
    <div class="col-md-4">
        <div class="card card-custom">
            <div class="card-body text-center">
                <div class="bg-primary-light rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 100px; height: 100px;">
                    <i class="fas fa-user fa-3x text-primary"></i>
                </div>
                <h4>{{ $user['nama'] }}</h4>
                <p class="text-muted">
                    @if($user['role'] == 'admin')
                        Administrator
                    @else
                        Asisten Praktikum / Anggota Lab
                    @endif
                </p>
                <div class="d-grid">
                    <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editProfileModal">
                        <i class="fas fa-edit me-2"></i> Edit Profil
                    </button>
                </div>
            </div>
        </div>

        <div class="card card-custom mt-4">
            <div class="card-header bg-white">
                <h5 class="card-title mb-0">Statistik Mengajar</h5>
            </div>
            <div class="card-body">
                @php
                    $jadwalSaya = array_filter($jadwal, function($item) use ($user) {
                        return $item['asprak'] == $user['nama'];
                    });
                    $totalMengajar = count($jadwalSaya);
                    $selesaiMengajar = count(array_filter($jadwalSaya, function($item) {
                        return $item['status'] === 'selesai';
                    }));
                    $belumMengajar = $totalMengajar - $selesaiMengajar;
                @endphp
                <div class="d-flex justify-content-between mb-3">
                    <span>Total Mengajar:</span>
                    <strong>{{ $totalMengajar }} Sesi</strong>
                </div>
                <div class="d-flex justify-content-between mb-3">
                    <span>Selesai:</span>
                    <strong>{{ $selesaiMengajar }} Sesi</strong>
                </div>
                <div class="d-flex justify-content-between">
                    <span>Belum:</span>
                    <strong>{{ $belumMengajar }} Sesi</strong>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card card-custom">
            <div class="card-header bg-white">
                <h5 class="card-title mb-0">Informasi Profil</h5>
            </div>
            <div class="card-body">
                <form>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" class="form-control" value="{{ $userData['nama'] ?? $user['nama'] }}" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">NIM</label>
                            <input type="text" class="form-control" value="{{ $userData['nim'] ?? '-' }}" readonly>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Jabatan</label>
                            <input type="text" class="form-control" value="{{ $userData['jabatan'] ?? ($user['role'] == 'admin' ? 'Administrator' : 'Asisten Praktikum') }}" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" value="{{ $userData['email'] ?? '-' }}" readonly>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Username</label>
                            <input type="text" class="form-control" value="{{ $user['username'] }}" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Role</label>
                            <input type="text" class="form-control" value="{{ $user['role'] == 'admin' ? 'Administrator' : 'Asisten Praktikum' }}" readonly>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Bio</label>
                        <textarea class="form-control" rows="3" readonly>{{ $userData ? 'Asisten Praktikum ' . $userData['jabatan'] . ' di Laboratorium Rekayasa Perangkat Lunak, Fakultas Ilmu Komputer, Universitas Jember.' : 'Pengguna sistem jadwal mengajar LAB RPL FASILKOM UNEJ.' }}</textarea>
                    </div>
                </form>
            </div>
        </div>

        <div class="card card-custom mt-4">
            <div class="card-header bg-white">
                <h5 class="card-title mb-0">Jadwal Mengajar Saya</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Hari</th>
                                <th>Waktu</th>
                                <th>Mata Kuliah</th>
                                <th>Ruang</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $jadwalSaya = array_filter($jadwal, function($item) use ($user) {
                                    return $item['asprak'] == $user['nama'];
                                });
                            @endphp

                            @if(count($jadwalSaya) > 0)
                                @foreach($jadwalSaya as $item)
                                <tr>
                                    <td>{{ $item['hari'] }}</td>
                                    <td>{{ $item['pukul'] }}</td>
                                    <td>{{ $item['mata_kuliah'] }}</td>
                                    <td>{{ $item['ruang'] }}</td>
                                    <td>
                                        <span class="status-badge {{ $item['status'] == 'selesai' ? 'status-selesai' : 'status-belum' }}">
                                            {{ $item['status'] == 'selesai' ? 'Selesai' : 'Belum' }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="5" class="text-center py-3 text-muted">
                                        <i class="fas fa-calendar-times me-2"></i> Tidak ada jadwal mengajar
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Edit Profil -->
<div class="modal fade" id="editProfileModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Profil</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('updateProfile') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" class="form-control" name="nama" value="{{ $userData['nama'] ?? $user['nama'] }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">NIM</label>
                        <input type="text" class="form-control" name="nim" value="{{ $userData['nim'] ?? '' }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Jabatan</label>
                        <input type="text" class="form-control" name="jabatan" value="{{ $userData['jabatan'] ?? ($user['role'] == 'admin' ? 'Administrator' : 'Asisten Praktikum') }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" name="email" value="{{ $userData['email'] ?? '' }}" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary-custom">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
