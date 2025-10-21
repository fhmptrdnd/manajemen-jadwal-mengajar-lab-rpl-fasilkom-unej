@extends('layouts.app')

@section('title', 'Pengelolaan Jadwal')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Pengelolaan Jadwal Mengajar</h1>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<ul class="nav nav-tabs mb-4" id="pengelolaanTab" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active" id="jadwal-tab" data-bs-toggle="tab" data-bs-target="#jadwal" type="button" role="tab">
            <i class="fas fa-calendar-alt me-2"></i>Jadwal Mengajar
        </button>
    </li>
    @if($user['role'] == 'admin')
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="anggota-tab" data-bs-toggle="tab" data-bs-target="#anggota" type="button" role="tab">
            <i class="fas fa-users me-2"></i>Anggota Lab
        </button>
    </li>
    @endif
</ul>

<div class="tab-content" id="pengelolaanTabContent">
    <!-- Tab Jadwal Mengajar -->
    <div class="tab-pane fade show active" id="jadwal" role="tabpanel">
        <div class="card card-custom">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Daftar Jadwal Mengajar</h5>
                @if($user['role'] == 'admin')
                <button class="btn btn-primary-custom btn-sm text-white" data-bs-toggle="modal" data-bs-target="#tambahJadwalModal">
                    <i class="fas fa-plus me-1"></i> Tambah Jadwal
                </button>
                @endif
            </div>
            <div class="card-body p-0"> <!-- Hapus padding di card body -->
                <div class="table-scroll-container">
                    <table class="table table-hover table-striped table-bordered mb-0" style="min-width: 1000px;">
                        <thead class="table-light">
                            <tr>
                                <th style="min-width: 100px;">Tanggal</th>
                                <th style="min-width: 80px;">Hari</th>
                                <th style="min-width: 120px;">Waktu</th>
                                <th style="min-width: 200px;">Mata Kuliah</th>
                                <th style="min-width: 150px;">Asprak</th>
                                <th style="min-width: 120px;">Ruang</th>
                                <th style="min-width: 100px;">Status</th>
                                <th style="min-width: 100px;">Resume</th>
                                <th style="min-width: 150px;" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($jadwal as $item)
                            <tr>
                                <td>{{ date('d/m/Y', strtotime($item['tanggal'] ?? now())) }}</td>
                                <td>{{ $item['hari'] ?? '-' }}</td>
                                <td>{{ $item['pukul'] ?? '-' }}</td>
                                <td class="text-truncate" style="max-width: 200px;" title="{{ $item['mata_kuliah'] ?? '-' }}">
                                    {{ $item['mata_kuliah'] ?? '-' }}
                                </td>
                                <td>{{ $item['asprak'] ?? '-' }}</td>
                                <td>{{ $item['ruang'] ?? '-' }}</td>
                                <td>
                                    <span class="status-badge {{ ($item['status'] ?? 'belum') == 'selesai' ? 'status-selesai' : 'status-belum' }}">
                                        {{ ($item['status'] ?? 'belum') == 'selesai' ? 'Selesai' : 'Belum' }}
                                    </span>
                                </td>
                                <td>
                                    @if(($item['status'] ?? 'belum') == 'selesai' && !empty($item['resume'] ?? ''))
                                        <span class="badge bg-info" data-bs-toggle="tooltip" title="{{ $item['resume'] ?? '' }}">
                                            <i class="fas fa-file-alt me-1"></i> Ada
                                        </span>
                                    @else
                                        <span class="badge bg-secondary">-</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex justify-content-center gap-1">
                                        @if($user['role'] == 'admin')
                                            <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editJadwalModal{{ $item['id'] ?? $loop->index }}" title="Edit Jadwal">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-success" data-bs-toggle="modal" data-bs-target="#updateStatusModal{{ $item['id'] ?? $loop->index }}" title="Update Status">
                                                <i class="fas fa-check"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#hapusJadwalModal{{ $item['id'] ?? $loop->index }}" title="Hapus Jadwal">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        @elseif($user['nama'] == ($item['asprak'] ?? ''))
                                            <button class="btn btn-sm btn-outline-success" data-bs-toggle="modal" data-bs-target="#updateStatusModal{{ $item['id'] ?? $loop->index }}" title="Update Status">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @if(count($jadwal) === 0)
                <div class="text-center py-4">
                    <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                    <p class="text-muted">Belum ada jadwal mengajar</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Tab Anggota Lab (Hanya Admin) -->
    @if($user['role'] == 'admin')
    <div class="tab-pane fade" id="anggota" role="tabpanel">
        <div class="card card-custom">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Daftar Anggota Lab</h5>
                <button class="btn btn-primary-custom btn-sm text-white" data-bs-toggle="modal" data-bs-target="#tambahAnggotaModal">
                    <i class="fas fa-user-plus me-1"></i> Tambah Anggota
                </button>
            </div>
            <div class="card-body p-0"> <!-- Hapus padding di card body -->
                <div class="table-scroll-container">
                    <table class="table table-hover table-striped table-bordered mb-0" style="min-width: 700px;">
                        <thead class="table-light">
                            <tr>
                                <th style="min-width: 150px;">Nama</th>
                                <th style="min-width: 120px;">NIM</th>
                                <th style="min-width: 150px;">Jabatan</th>
                                <th style="min-width: 200px;">Email</th>
                                <th style="min-width: 120px;" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($anggota_lab as $anggota)
                            <tr>
                                <td>{{ $anggota['nama'] ?? '-' }}</td>
                                <td>{{ $anggota['nim'] ?? '-' }}</td>
                                <td>{{ $anggota['jabatan'] ?? '-' }}</td>
                                <td class="text-truncate" style="max-width: 200px;" title="{{ $anggota['email'] ?? '-' }}">
                                    {{ $anggota['email'] ?? '-' }}
                                </td>
                                <td>
                                    <div class="d-flex justify-content-center gap-1">
                                        <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editAnggotaModal{{ $anggota['id'] ?? $loop->index }}" title="Edit Anggota">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#hapusAnggotaModal{{ $anggota['id'] ?? $loop->index }}" title="Hapus Anggota">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @if(count($anggota_lab) === 0)
                <div class="text-center py-4">
                    <i class="fas fa-users-slash fa-3x text-muted mb-3"></i>
                    <p class="text-muted">Belum ada anggota lab</p>
                </div>
                @endif
            </div>
        </div>
    </div>
    @endif
</div>

<!-- Modal Tambah Jadwal (Admin) -->
@if($user['role'] == 'admin')
<div class="modal fade" id="tambahJadwalModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Jadwal Mengajar</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('updateJadwal') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Asprak</label>
                        <select class="form-select" name="asprak" required>
                            @foreach($anggota_lab as $anggota)
                            <option value="{{ $anggota['nama'] ?? '' }}">{{ $anggota['nama'] ?? '-' }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Hari</label>
                        <select class="form-select" name="hari" required>
                            <option value="Senin">Senin</option>
                            <option value="Selasa">Selasa</option>
                            <option value="Rabu">Rabu</option>
                            <option value="Kamis">Kamis</option>
                            <option value="Jumat">Jumat</option>
                            <option value="Sabtu">Sabtu</option>
                            <option value="Minggu">Minggu</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tanggal</label>
                        <input type="date" class="form-control" name="tanggal" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Waktu</label>
                        <input type="text" class="form-control" name="pukul" placeholder="Contoh: 08:00 - 10:00" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Mata Kuliah</label>
                        <input type="text" class="form-control" name="mata_kuliah" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Ruang</label>
                        <input type="text" class="form-control" name="ruang" placeholder="Contoh: Lab RPL A" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary-custom text-white">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- nambah anggota buat admin -->
<div class="modal fade" id="tambahAnggotaModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Anggota Lab</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('tambahAnggota') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" class="form-control" name="nama" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">NIM</label>
                        <input type="text" class="form-control" name="nim" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Jabatan</label>
                        <select class="form-select" name="jabatan" required>
                            <option value="Koordinator Lab">Koordinator Lab</option>
                            <option value="Asisten Lab">Asisten Lab</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" name="email" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary-custom text-white">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

<!-- ngedit jadwal buat admin -->
@if($user['role'] == 'admin')
    @foreach($jadwal as $item)
    <div class="modal fade" id="editJadwalModal{{ $item['id'] ?? $loop->index }}" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Jadwal Mengajar</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('updateJadwal') }}" method="POST">
                    @csrf
                    <input type="hidden" name="id" value="{{ $item['id'] ?? '' }}">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Asprak</label>
                            <select class="form-select" name="asprak" required>
                                @foreach($anggota_lab as $anggota)
                                <option value="{{ $anggota['nama'] ?? '' }}" {{ ($anggota['nama'] ?? '') == ($item['asprak'] ?? '') ? 'selected' : '' }}>
                                    {{ $anggota['nama'] ?? '-' }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Hari</label>
                            <select class="form-select" name="hari" required>
                                <option value="Senin" {{ ($item['hari'] ?? '') == 'Senin' ? 'selected' : '' }}>Senin</option>
                                <option value="Selasa" {{ ($item['hari'] ?? '') == 'Selasa' ? 'selected' : '' }}>Selasa</option>
                                <option value="Rabu" {{ ($item['hari'] ?? '') == 'Rabu' ? 'selected' : '' }}>Rabu</option>
                                <option value="Kamis" {{ ($item['hari'] ?? '') == 'Kamis' ? 'selected' : '' }}>Kamis</option>
                                <option value="Jumat" {{ ($item['hari'] ?? '') == 'Jumat' ? 'selected' : '' }}>Jumat</option>
                                <option value="Sabtu" {{ ($item['hari'] ?? '') == 'Sabtu' ? 'selected' : '' }}>Sabtu</option>
                                <option value="Minggu" {{ ($item['hari'] ?? '') == 'Minggu' ? 'selected' : '' }}>Minggu</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tanggal</label>
                            <input type="date" class="form-control" name="tanggal" value="{{ $item['tanggal'] ?? date('Y-m-d') }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Waktu</label>
                            <input type="text" class="form-control" name="pukul" value="{{ $item['pukul'] ?? '' }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Mata Kuliah</label>
                            <input type="text" class="form-control" name="mata_kuliah" value="{{ $item['mata_kuliah'] ?? '' }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Ruang</label>
                            <input type="text" class="form-control" name="ruang" value="{{ $item['ruang'] ?? '' }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select class="form-select" name="status" required>
                                <option value="belum" {{ ($item['status'] ?? 'belum') == 'belum' ? 'selected' : '' }}>Belum Dilaksanakan</option>
                                <option value="selesai" {{ ($item['status'] ?? 'belum') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Resume Kegiatan</label>
                            <textarea class="form-control" name="resume" rows="3" placeholder="Ringkasan materi yang diajarkan...">{{ $item['resume'] ?? '' }}</textarea>
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
    @endforeach
@endif

<!-- update status buat semua -->
@foreach($jadwal as $item)
    @if($user['role'] == 'admin' || $user['nama'] == ($item['asprak'] ?? ''))
    <div class="modal fade" id="updateStatusModal{{ $item['id'] ?? $loop->index }}" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Update Status Mengajar</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('updateStatus') }}" method="POST">
                    @csrf
                    <input type="hidden" name="id" value="{{ $item['id'] ?? '' }}">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select class="form-select" name="status" required>
                                <option value="belum" {{ ($item['status'] ?? 'belum') == 'belum' ? 'selected' : '' }}>Belum Dilaksanakan</option>
                                <option value="selesai" {{ ($item['status'] ?? 'belum') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Resume Kegiatan</label>
                            <textarea class="form-control" name="resume" rows="3" placeholder="Ringkasan materi yang diajarkan...">{{ $item['resume'] ?? '' }}</textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary-custom">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
@endforeach

<!-- hapus jadwal buat admin -->
@if($user['role'] == 'admin')
    @foreach($jadwal as $item)
    <div class="modal fade" id="hapusJadwalModal{{ $item['id'] ?? $loop->index }}" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Hapus Jadwal</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menghapus jadwal ini?</p>
                    <ul>
                        <li>Mata Kuliah: <strong>{{ $item['mata_kuliah'] ?? '-' }}</strong></li>
                        <li>Hari: <strong>{{ $item['hari'] ?? '-' }}</strong></li>
                        <li>Tanggal: <strong>{{ date('d/m/Y', strtotime($item['tanggal'] ?? now())) }}</strong></li>
                        <li>Waktu: <strong>{{ $item['pukul'] ?? '-' }}</strong></li>
                        <li>Asprak: <strong>{{ $item['asprak'] ?? '-' }}</strong></li>
                    </ul>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <form action="{{ route('hapusJadwal') }}" method="POST">
                        @csrf
                        <input type="hidden" name="id" value="{{ $item['id'] ?? '' }}">
                        <button type="submit" class="btn btn-danger">Hapus</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endforeach
@endif

<!-- Modal Edit Anggota (Admin) -->
@if($user['role'] == 'admin')
    @foreach($anggota_lab as $anggota)
    <div class="modal fade" id="editAnggotaModal{{ $anggota['id'] ?? $loop->index }}" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Anggota Lab</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('updateAnggota') }}" method="POST">
                    @csrf
                    <input type="hidden" name="id" value="{{ $anggota['id'] ?? '' }}">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" class="form-control" name="nama" value="{{ $anggota['nama'] ?? '' }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">NIM</label>
                            <input type="text" class="form-control" name="nim" value="{{ $anggota['nim'] ?? '' }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Jabatan</label>
                            <select class="form-select" name="jabatan" required>
                                <option value="Koordinator Lab" {{ ($anggota['jabatan'] ?? '') == 'Koordinator Lab' ? 'selected' : '' }}>Koordinator Lab</option>
                                <option value="Asisten Lab" {{ ($anggota['jabatan'] ?? '') == 'Asisten Lab' ? 'selected' : '' }}>Asisten Lab</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" name="email" value="{{ $anggota['email'] ?? '' }}" required>
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

    <!-- Modal Hapus Anggota (Admin) -->
    <div class="modal fade" id="hapusAnggotaModal{{ $anggota['id'] ?? $loop->index }}" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Hapus Anggota Lab</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menghapus anggota <strong>{{ $anggota['nama'] ?? 'Unknown' }}</strong>?</p>
                    <p class="text-danger"><small>Data yang dihapus tidak dapat dikembalikan.</small></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <form action="{{ route('hapusAnggota') }}" method="POST">
                        @csrf
                        <input type="hidden" name="id" value="{{ $anggota['id'] ?? '' }}">
                        <button type="submit" class="btn btn-danger">Hapus</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endforeach
@endif

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Inisialisasi tooltip
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });

        const tableContainers = document.querySelectorAll('.table-scroll-container');

        tableContainers.forEach(container => {
            const canScroll = container.scrollWidth > container.clientWidth;

            if (canScroll) {
                container.style.borderRight = '2px solid #e9ecef';
            }

            // event listener buat scroll
            container.addEventListener('scroll', function() {
                const maxScroll = this.scrollWidth - this.clientWidth;

                if (this.scrollLeft > 0) {
                    this.style.borderLeft = '2px solid #e9ecef';
                } else {
                    this.style.borderLeft = 'none';
                }

                if (this.scrollLeft < maxScroll) {
                    this.style.borderRight = '2px solid #e9ecef';
                } else {
                    this.style.borderRight = 'none';
                }
            });
        });
    });

    // load ulang waktu pindah tab
    var tabEl = document.querySelector('button[data-bs-toggle="tab"]');
    if (tabEl) {
        tabEl.addEventListener('shown.bs.tab', function() {
            setTimeout(function() {
                const tableContainers = document.querySelectorAll('.table-scroll-container');
                tableContainers.forEach(container => {
                    const canScroll = container.scrollWidth > container.clientWidth;
                    if (canScroll) {
                        container.style.borderRight = '2px solid #e9ecef';
                    }
                });
            }, 50);
        });
    }
</script>
@endpush

<style>
.table-scroll-container {
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
    background: white;
    position: relative;
    border-left: none;
    border-right: none;
    transition: border 0.2s ease;
}

.table-scroll-container::-webkit-scrollbar {
    height: 8px;
}

.table-scroll-container::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 4px;
    margin: 0 2px;
}

.table-scroll-container::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 4px;
}

.table-scroll-container::-webkit-scrollbar-thumb:hover {
    background: #a8a8a8;
}

.table-scroll-container {
    scrollbar-width: thin;
    scrollbar-color: #c1c1c1 #f1f1f1;
}

@media (max-width: 768px) {
    .table-scroll-container {
        font-size: 0.875rem;
    }

    .table-scroll-container:first-child table {
        min-width: 1000px;
    }

    .table-scroll-container:last-child table {
        min-width: 700px;
    }

    .table-scroll-container table {
        min-width: 600px;
    }

    .card-body.p-0 {
        padding: 0 !important;
    }
}

@media (min-width: 769px) {
    .table-scroll-container {
        max-height: 600px;
        overflow-y: auto;
    }
    .dashboard .table-scroll-container {
        max-height: 400px;
    }
}

.table-scroll-container table {
    margin-bottom: 0;
    white-space: nowrap;
    width: 100%;
}
</style>
