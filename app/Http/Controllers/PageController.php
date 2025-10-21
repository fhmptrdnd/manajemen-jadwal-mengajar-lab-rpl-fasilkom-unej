<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Routing\Controller;

class PageController extends Controller
{
    private $users = [
        ['username' => 'admin', 'password' => 'admin123', 'role' => 'admin', 'nama' => 'Nala Abighala'],
        ['username' => 'asprak1', 'password' => 'asprak123', 'role' => 'anggota', 'nama' => 'Fahmi Putra'],
        ['username' => 'asprak2', 'password' => 'asprak456', 'role' => 'anggota', 'nama' => 'Kashimura Sana'],
        ['username' => 'asprak3', 'password' => 'asprak789', 'role' => 'anggota', 'nama' => 'Hannah Lily']
    ];

    private function initializeJadwal()
    {
        Session::put('jadwal', [
            [
                'id' => 1,
                'asprak' => 'Fahmi Putra',
                'hari' => 'Rabu',
                'tanggal' => date('Y-m-d', strtotime('last monday')),
                'pukul' => '18:00 - 20:40',
                'mata_kuliah' => 'Bahasa Pemrograman',
                'ruang' => 'A3.3',
                'status' => 'belum',
                'resume' => ''
            ],
            [
                'id' => 2,
                'asprak' => 'Hannah Lily',
                'hari' => 'Rabu',
                'tanggal' => date('Y-m-d', strtotime('last monday')),
                'pukul' => '18:00 - 20:40',
                'mata_kuliah' => 'Bahasa Pemrograman',
                'ruang' => 'A3.3',
                'status' => 'belum',
                'resume' => ''
            ],
            [
                'id' => 3,
                'asprak' => 'Kashimura Sana',
                'hari' => 'Selasa',
                'tanggal' => date('Y-m-d', strtotime('last tuesday')),
                'pukul' => '08:00 - 10:00',
                'mata_kuliah' => 'Algoritma',
                'ruang' => 'Lab RPL A',
                'status' => 'selesai',
                'resume' => 'Praktikum sorting'
            ],
            [
                'id' => 4,
                'asprak' => 'Fahmi Putra',
                'hari' => 'Senin',
                'tanggal' => date('Y-m-d', strtotime('last wednesday')),
                'pukul' => '13:00 - 15:00',
                'mata_kuliah' => 'Framework Development',
                'ruang' => 'Lab RPL A',
                'status' => 'belum',
                'resume' => ''
            ],
            [
                'id' => 5,
                'asprak' => 'Hannah Lily',
                'hari' => 'Kamis',
                'tanggal' => date('Y-m-d', strtotime('last thursday')),
                'pukul' => '10:00 - 12:00',
                'mata_kuliah' => 'Data Mining',
                'ruang' => 'Lab RPL B',
                'status' => 'belum',
                'resume' => ''
            ]
        ]);
    }

    private function initializeAnggotaLab()
    {
        Session::put('anggota_lab', [
            ['id' => 1, 'nama' => 'Nala Abighala', 'nim' => '222410102019', 'jabatan' => 'Koordinator Lab', 'email' => 'nala.abighala@unej.ac.id'],
            ['id' => 2, 'nama' => 'Hannah Lily', 'nim' => '242410101058', 'jabatan' => 'Anggota Lab', 'email' => 'hannah.lily@unej.ac.id'],
            ['id' => 3, 'nama' => 'Kashimura Sana', 'nim' => '242410103026', 'jabatan' => 'Anggota Lab', 'email' => 'kashimura.sana@unej.ac.id'],
            ['id' => 4, 'nama' => 'Fahmi Putra', 'nim' => '242410103027', 'jabatan' => 'Anggota Lab', 'email' => 'fahmi.putra@unej.ac.id']
        ]);
    }

    public function __construct()
    {
        // inisialisasi data jadwal jika belum ada, dipanggil setiap page controller dibuat
        if (!Session::has('jadwal') || empty(Session::get('jadwal'))) {
            $this->initializeJadwal();
        }

        // inisialisasi data anggota lab jika belum ada, sama kayak yang atas
        if (!Session::has('anggota_lab') || empty(Session::get('anggota_lab'))) {
            $this->initializeAnggotaLab();
        }

        // validasi dan perbaiki struktur data sesi, ini fungsi internal
        $this->validateAndFixSessionData();
    }

    private function validateAndFixSessionData()
    {
        // validasi dan perbaiki data jadwal
        $jadwal = Session::get('jadwal', []);
        $fixedJadwal = [];

        /* perulangan pada setiap item di array jadwal, dipecah sbgai index yang merujuk pada item pada array
           ini buat cek apa strukturnya valid atau ngga, kalau key ada value, bakal diisi sesuai array, kalau gaada diganti
           default setelah tanda ??*/
        foreach ($jadwal as $index => $item) {
            $fixedJadwal[] = [
                'id' => $item['id'] ?? $index + 1,
                'asprak' => $item['asprak'] ?? 'Unknown',
                'hari' => $item['hari'] ?? 'Senin',
                'tanggal' => $item['tanggal'] ?? date('Y-m-d'),
                'pukul' => $item['pukul'] ?? '08:00 - 10:00',
                'mata_kuliah' => $item['mata_kuliah'] ?? 'Unknown',
                'ruang' => $item['ruang'] ?? 'Lab RPL A',
                'status' => $item['status'] ?? 'belum',
                'resume' => $item['resume'] ?? ''
            ];
        }

        Session::put('jadwal', $fixedJadwal); //ngisi array fixedJadwal pakai key jadwal ke session, nimpa jadwal lama juga

        // validasi dan perbaiki data anggota_lab, samaa kayak di atas
        $anggotaLab = Session::get('anggota_lab', []);
        $fixedAnggotaLab = [];

        foreach ($anggotaLab as $index => $anggota) {
            $fixedAnggotaLab[] = [
                'id' => $anggota['id'] ?? $index + 1,
                'nama' => $anggota['nama'] ?? 'Anggota ' . ($index + 1),
                'nim' => $anggota['nim'] ?? '000000000000',
                'jabatan' => $anggota['jabatan'] ?? 'Anggota Lab',
                'email' => $anggota['email'] ?? 'default@unej.ac.id'
            ];
        }

        Session::put('anggota_lab', $fixedAnggotaLab);
    }

    public function login()
    {
        return view('login');
    }

    public function doLogin(Request $request)
    {
        $username = $request->input('username'); //minta data dari form
        $password = $request->input('password');

        //cek apakah username sm password ada yg cocok apa ngga dari array
        foreach ($this->users as $user) {
            if ($user['username'] === $username && $user['password'] === $password) {
                Session::put('user', $user);
                return redirect()->route('dashboard')->with('username', $user['nama']); //kalau cocok lanjut dashboard
            }
        }

        return redirect()->route('login')->with('error', 'Username atau password salah');
    }

    private function getHariIndonesia($dayOfWeek)
    {
        $hari = [
            0 => 'Minggu',
            1 => 'Senin',
            2 => 'Selasa',
            3 => 'Rabu',
            4 => 'Kamis',
            5 => 'Jumat',
            6 => 'Sabtu'
        ];

        return $hari[$dayOfWeek] ?? 'Unknown';
    }

    public function dashboard(Request $request)
    {
        if (!Session::has('user')) {
            return redirect()->route('login');
        } //cek ulang apa sesi saat ini punya key user

        $user = Session::get('user');
        $username = $request->query('username', $user['nama']);

        $jadwal = Session::get('jadwal', []);

        $totalJadwal = count($jadwal);
        $jadwalSelesai = count(array_filter($jadwal, function($item) {
            return $item['status'] === 'selesai';
        }));
        $jadwalBelum = $totalJadwal - $jadwalSelesai;

        // jadwal realtime hari ini
        $hariIni = $this->getHariIndonesia(now()->dayOfWeek);
        $jadwalHariIni = array_filter($jadwal, function($item) use ($hariIni) {
            return $item['hari'] === $hariIni; //cuma return item jadwl yg key harinya cocok sama $hariIni
        });

        // data anggota lab buat sidebar
        $anggotaLab = Session::get('anggota_lab', []);

        return view('dashboard', compact(
            'username',
            'user',
            'totalJadwal',
            'jadwalSelesai',
            'jadwalBelum',
            'jadwalHariIni',
            'hariIni',
            'anggotaLab'
        ));
    }

    public function pengelolaan()
    {
        if (!Session::has('user')) {
            return redirect()->route('login');
        }

        $user = Session::get('user');

        $jadwal = Session::get('jadwal', []);
        $anggotaLab = Session::get('anggota_lab', []);

        return view('pengelolaan', [
            'user' => $user,
            'jadwal' => $jadwal,
            'anggota_lab' => $anggotaLab
        ]);
    }

    public function profile()
    {
        if (!Session::has('user')) {
            return redirect()->route('login');
        }

        $user = Session::get('user');

        $anggotaLab = Session::get('anggota_lab', []);
        $userData = array_filter($anggotaLab, function($anggota) use ($user) {
            return $anggota['nama'] === $user['nama'];
        });

        $userData = !empty($userData) ? array_values($userData)[0] : null; //reset index key setelah difilter

        $jadwal = Session::get('jadwal', []);
        return view('profile', compact('user', 'userData', 'jadwal'));
    }

    public function updateProfile(Request $request)
    {
        if (!Session::has('user')) {
            return redirect()->route('login');
        }

        $user = Session::get('user');
        $anggotaLab = Session::get('anggota_lab', []);

        $nama = $request->input('nama');
        $nim = $request->input('nim');
        $jabatan = $request->input('jabatan');
        $email = $request->input('email');

        $user['nama'] = $nama;
        Session::put('user', $user);

        $originalNama = $user['nama']; // simpan nama asli buat pencarian
        foreach ($anggotaLab as &$anggota) {
            if ($anggota['nama'] === $originalNama) {
                $anggota['nama'] = $nama;
                $anggota['nim'] = $nim;
                $anggota['jabatan'] = $jabatan;
                $anggota['email'] = $email;
                break;
            }
        }

        Session::put('anggota_lab', $anggotaLab);

        return redirect()->route('profile')->with('success', 'Profil berhasil diperbarui');
    }

    public function updateStatus(Request $request)
    {
        if (!Session::has('user')) {
            return redirect()->route('login');
        }

        $id = (int)$request->input('id');
        $status = $request->input('status');
        $resume = $request->input('resume', '');

        $jadwal = Session::get('jadwal', []);

        foreach ($jadwal as &$item) {
            if ($item['id'] === $id) {
                $item['status'] = $status;
                $item['resume'] = $resume;
                break;
            }
        }

        Session::put('jadwal', $jadwal);

        return redirect()->route('pengelolaan')->with('success', 'Status jadwal berhasil diperbarui');
    }

    public function updateJadwal(Request $request)
    {
        if (!Session::has('user')) {
            return redirect()->route('login');
        }

        $user = Session::get('user');
        if ($user['role'] !== 'admin') {
            return redirect()->route('pengelolaan')->with('error', 'Anda tidak memiliki akses untuk mengubah jadwal');
        }

        $id = $request->input('id') ? (int)$request->input('id') : null;
        $asprak = $request->input('asprak');
        $hari = $request->input('hari');
        $tanggal = $request->input('tanggal');
        $pukul = $request->input('pukul');
        $mataKuliah = $request->input('mata_kuliah');
        $ruang = $request->input('ruang');
        $status = $request->input('status', 'belum');
        $resume = $request->input('resume', '');

        $jadwal = Session::get('jadwal', []);

        if ($id) {
            // edit
            foreach ($jadwal as &$item) {
                if ($item['id'] === $id) {
                    $item['asprak'] = $asprak;
                    $item['hari'] = $hari;
                    $item['tanggal'] = $tanggal;
                    $item['pukul'] = $pukul;
                    $item['mata_kuliah'] = $mataKuliah;
                    $item['ruang'] = $ruang;
                    $item['status'] = $status;
                    $item['resume'] = $resume;
                    break;
                }
            }
        } else {
            // tmbah
            $newId = count($jadwal) + 1;
            $jadwal[] = [
                'id' => $newId,
                'asprak' => $asprak,
                'hari' => $hari,
                'tanggal' => $tanggal,
                'pukul' => $pukul,
                'mata_kuliah' => $mataKuliah,
                'ruang' => $ruang,
                'status' => $status,
                'resume' => $resume
            ];
        }

        Session::put('jadwal', $jadwal);

        return redirect()->route('pengelolaan')->with('success', 'Jadwal berhasil diperbarui');
    }

    public function hapusJadwal(Request $request)
    {
        if (!Session::has('user')) {
            return redirect()->route('login');
        }

        $user = Session::get('user');
        if ($user['role'] !== 'admin') {
            return redirect()->route('pengelolaan')->with('error', 'Anda tidak memiliki akses untuk menghapus jadwal');
        }

        $id = (int)$request->input('id');
        $jadwal = Session::get('jadwal', []);

        $jadwal = array_filter($jadwal, function($item) use ($id) {
            return $item['id'] !== $id;
        });

        //reset key & id untuk operasi lanjutan habis suatu jadwal dihapus
        $jadwal = array_values($jadwal);
        foreach ($jadwal as $index => &$item) {
            $item['id'] = $index + 1;
        }

        Session::put('jadwal', $jadwal);

        return redirect()->route('pengelolaan')->with('success', 'Jadwal berhasil dihapus');
    }

    public function tambahAnggota(Request $request)
    {
        if (!Session::has('user')) {
            return redirect()->route('login');
        }

        $user = Session::get('user');
        if ($user['role'] !== 'admin') {
            return redirect()->route('pengelolaan')->with('error', 'Anda tidak memiliki akses untuk menambah anggota');
        }

        $nama = $request->input('nama');
        $nim = $request->input('nim');
        $jabatan = $request->input('jabatan');
        $email = $request->input('email');

        $anggotaLab = Session::get('anggota_lab', []);
        $newId = count($anggotaLab) + 1;

        $anggotaLab[] = [
            'id' => $newId,
            'nama' => $nama,
            'nim' => $nim,
            'jabatan' => $jabatan,
            'email' => $email
        ];

        Session::put('anggota_lab', $anggotaLab);

        return redirect()->route('pengelolaan')->with('success', 'Anggota lab berhasil ditambahkan');
    }

    public function updateAnggota(Request $request)
    {
        if (!Session::has('user')) {
            return redirect()->route('login');
        }

        $user = Session::get('user');
        if ($user['role'] !== 'admin') {
            return redirect()->route('pengelolaan')->with('error', 'Anda tidak memiliki akses untuk mengubah data anggota');
        }

        $id = (int)$request->input('id');
        $nama = $request->input('nama');
        $nim = $request->input('nim');
        $jabatan = $request->input('jabatan');
        $email = $request->input('email');

        $anggotaLab = Session::get('anggota_lab', []);

        foreach ($anggotaLab as &$anggota) {
            if ($anggota['id'] === $id) {
                $anggota['nama'] = $nama;
                $anggota['nim'] = $nim;
                $anggota['jabatan'] = $jabatan;
                $anggota['email'] = $email;
                break;
            }
        }

        Session::put('anggota_lab', $anggotaLab);

        return redirect()->route('pengelolaan')->with('success', 'Data anggota berhasil diperbarui');
    }

    public function hapusAnggota(Request $request)
    {
        if (!Session::has('user')) {
            return redirect()->route('login');
        }

        $user = Session::get('user');
        if ($user['role'] !== 'admin') {
            return redirect()->route('pengelolaan')->with('error', 'Anda tidak memiliki akses untuk menghapus anggota');
        }

        $id = (int)$request->input('id');
        $anggotaLab = Session::get('anggota_lab', []);

        $anggotaLab = array_filter($anggotaLab, function($anggota) use ($id) {
            return $anggota['id'] !== $id;
        });

        $anggotaLab = array_values($anggotaLab);
        foreach ($anggotaLab as $index => &$anggota) {
            $anggota['id'] = $index + 1;
        }

        Session::put('anggota_lab', $anggotaLab);

        return redirect()->route('pengelolaan')->with('success', 'Anggota lab berhasil dihapus');
    }

    public function logout()
    {
        Session::forget('user');
        return redirect()->route('login');
    }

    // public function resetData()
    // {
    //     Session::forget('jadwal');
    //     Session::forget('anggota_lab');
    //     $this->initializeJadwal();
    //     $this->initializeAnggotaLab();

    //     return redirect()->route('pengelolaan')->with('success', 'Data berhasil direset ke keadaan awal');
    // }
}
