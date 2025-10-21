@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Dashboard</h1>
</div>

<div class="row mb-4">
    <div class="col-md-12">
        <div class="alert alert-primary d-flex align-items-center" role="alert">
            <i class="fas fa-info-circle me-2 fs-4"></i>
            <div>
                <h5 class="alert-heading mb-1">Selamat datang, {{ $username }}!</h5>
                <p class="mb-0">Sistem Manajemen Jadwal Mengajar LAB RPL FASILKOM UNEJ</p>
            </div>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-4 mb-3">
        <div class="card card-custom bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="card-title">{{ $totalJadwal }}</h4>
                        <p class="card-text">Total Jadwal</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-calendar-alt fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="card card-custom bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="card-title">{{ $jadwalSelesai }}</h4>
                        <p class="card-text">Jadwal Selesai</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-check-circle fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="card card-custom bg-warning text-dark">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="card-title">{{ $jadwalBelum }}</h4>
                        <p class="card-text">Jadwal Belum Dilaksanakan</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-clock fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card card-custom">
            <div class="card-header bg-white">
                <h5 class="card-title mb-0"><i class="fas fa-calendar-day me-2"></i>Jadwal Mengajar Hari Ini</h5>
            </div>
            <div class="card-body p-0">
                @if(count($jadwalHariIni) > 0)
                    <div class="table-scroll-container">
                        <table class="table table-hover table-striped table-bordered mb-0" style="min-width: 600px;">
                            <thead>
                                <tr>
                                    <th>Waktu</th>
                                    <th>Mata Kuliah</th>
                                    <th>Asprak</th>
                                    <th>Ruang</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($jadwalHariIni as $item)
                                <tr>
                                    <td>{{ $item['pukul'] }}</td>
                                    <td>{{ $item['mata_kuliah'] }}</td>
                                    <td>{{ $item['asprak'] }}</td>
                                    <td>{{ $item['ruang'] }}</td>
                                    <td>
                                        <span class="status-badge {{ $item['status'] == 'selesai' ? 'status-selesai' : 'status-belum' }}">
                                            {{ $item['status'] == 'selesai' ? 'Selesai' : 'Belum' }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                        <p class="text-muted">Tidak ada jadwal mengajar hari ini</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-4 mt-sm-0 mt-4">
        <div class="card card-custom">
            <div class="card-header bg-white">
                <h5 class="card-title mb-0"><i class="fas fa-users me-2"></i>Anggota Lab</h5>
            </div>
            <div class="card-body">
                <div class="list-group list-group-flush">
                    @php
                        $anggota_lab = [
                            ['nama' => 'Nala Abighala', 'jabatan' => 'Koordinator Lab'],
                            ['nama' => 'Fahmi Putra', 'jabatan' => 'Anggota Lab'],
                            ['nama' => 'Hannah Lily', 'jabatan' => 'Anggota Lab'],
                            ['nama' => 'Kashimura Sana', 'jabatan' => 'Anggota Lab']
                        ];
                    @endphp

                    @foreach($anggota_lab as $anggota)
                    <div class="list-group-item d-flex align-items-center px-0">
                        <div class="flex-shrink-0">
                            <div class="bg-primary-light rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                <i class="fas fa-user text-primary"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-0">{{ $anggota['nama'] }}</h6>
                            <small class="text-muted">{{ $anggota['jabatan'] }}</small>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
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

            // event listener scroll
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
