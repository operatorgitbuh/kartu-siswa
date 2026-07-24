<?php

namespace App\Http\Controllers;

use App\Models\Card;
// use App\Models\Student;
use App\Models\Background;
use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
// use Barryvdh\Snappy\Facades\SnappyPdf;
// use Picqer\Barcode\BarcodeGeneratorPNG;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class CardController extends Controller
{
    public function index(Request $request)
    {
        // 1. Update status kartu yang sudah kadaluarsa secara otomatis
        \App\Models\Card::where('status', 'active')
            ->whereNotNull('exp_date')
            ->where('exp_date', '<', now())
            ->update(['status' => 'expired']);

        // 2. Ambil semua parameter filter dari request
        $perPage = $request->get('perPage', 5);
        $filterKelas = $request->get('kelas');
        $filterJurusan = $request->get('jurusan');
        $filterStatus = $request->get('status', 'active');
        $search = $request->get('search'); // Tangkap input pencarian

        // 3. Bangun Query utama untuk data kartu
        $cards = \App\Models\Card::with(['student.classroom', 'background'])
            ->where('status', $filterStatus)

            // LOGIKA PENCARIAN NAMA SISWA
            ->when($search, function ($query) use ($search) {
                $query->whereHas('student', function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%');
                });
            })

            // Filter berdasarkan tingkat kelas (X, XI, XII)
            ->when($filterKelas, function ($query) use ($filterKelas) {
                $query->whereHas('student.classroom', function ($q) use ($filterKelas) {
                    $q->where('classroom', $filterKelas);
                });
            })

            // Filter berdasarkan kode jurusan
            ->when($filterJurusan, function ($query) use ($filterJurusan) {
                $query->whereHas('student.classroom', function ($q) use ($filterJurusan) {
                    $q->where('code_classroom', $filterJurusan);
                });
            })
            ->latest()
            ->paginate($perPage)
            ->withQueryString(); // SANGAT PENTING: Menjaga filter tetap aktif saat pindah halaman

        // 4. Data pendukung lainnya
        $countActive = \App\Models\Card::where('status', 'active')->count();
        $countExpired = \App\Models\Card::where('status', 'expired')->count();

        $students = \App\Models\Student::with('classroom')
            ->whereDoesntHave('card')
            ->get();

        $classrooms = \App\Models\Classroom::select('code_classroom')
            ->distinct()
            ->get();

        $backgrounds = \App\Models\Background::where('is_active', 1)
            ->get();

        return view('pages.cards.index', compact(
            'cards',
            'students',
            'backgrounds',
            'classrooms',
            'countActive',
            'countExpired'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id|unique:cards,student_id',
            'background_id' => 'nullable|exists:backgrounds,id',
            'foto' => 'nullable|image|max:2048',
            'exp_date' => 'nullable|date',
            'status' => 'required|in:active,expired',
        ]);

        $school = School::first();
        if (!$school) {
            return back()->with('error', 'Waduh min, isi dulu data profil sekolah di tabel schools ya!');
        }

        $data = $request->all();
        $data['school_id'] = $school->id;
        $data['exp_date'] = $request->exp_date ?? now()->addYears(3)->format('Y-m-d');

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('student_photos', 'public');
        }

        Card::create($data);

        return back()->with('success', 'Kartu siswa SMK Negeri 1 Wonosari berhasil diterbitkan!');
    }

    public function update(Request $request, $id)
    {
        $card = Card::findOrFail($id);

        $request->validate([
            'student_id' => 'required|exists:students,id|unique:cards,student_id,' . $id,
            'background_id' => 'nullable|exists:backgrounds,id',
            'foto' => 'nullable|image|max:2048',
            'exp_date' => 'nullable|date',
            'status' => 'required|in:active,expired',
        ]);

        $data = $request->all();

        if ($request->hasFile('foto')) {
            // Hapus foto lama jika ada
            if ($card->foto) {
                Storage::disk('public')->delete($card->foto);
            }
            // Simpan foto baru
            $data['foto'] = $request->file('foto')->store('student_photos', 'public');
        } else {
            // Tetap gunakan foto lama jika tidak ada upload baru
            $data['foto'] = $card->foto;
        }

        $card->update($data);

        return back()->with('success', 'Data kartu siswa berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $card = Card::findOrFail($id);
        $currentPage = (int) request()->get('page', 1);
        $perPage = (int) request()->get('perPage', 5);
        $search = request()->get('search');

        if ($card->foto) {
            Storage::disk('public')->delete($card->foto);
        }

        $card->delete();

        $remainingTotal = Card::where('school_id', auth()->user()?->school_id)->count();

        $maxPageAvailable = $remainingTotal > 0 ? ceil($remainingTotal / $perPage) : 1;

        $targetPage = $currentPage > $maxPageAvailable ? $maxPageAvailable : $currentPage;

        $url = route('card-students.index') . '?' . http_build_query([
            'page' => $targetPage,
            'perPage' => $perPage,
            'search' => $search
        ]);

        return redirect()->to($url)->with('success', 'Kartu berhasil dihapus!');
    }

    public function downloadPDF($id)
    {
        $DownloadPDF = Card::with(['student.classroom', 'school', 'background'])->findOrFail($id);

        $backgrounds = $DownloadPDF->background ?? Background::where('is_active', true)->first();

        $pageTitle = $DownloadPDF->student->name;
        $nisn = $DownloadPDF->student->nisn ?? '0000000000';
        $key_qrcode = $DownloadPDF->student->qrcode ?? '0000000000';

        $generator = new \Picqer\Barcode\BarcodeGeneratorPNG();
        $barcode = base64_encode($generator->getBarcode($nisn, $generator::TYPE_CODE_128));

        $qrcode = \SimpleSoftwareIO\QrCode\Facades\QrCode::size(200)
            ->gradient(0, 100, 102, 0, 0, 0, 'vertical')
            ->generate($key_qrcode);

        $pdf = \Barryvdh\Snappy\Facades\SnappyPdf::loadView('pages.cards.download.pdf-cards', compact(
            'DownloadPDF',
            'qrcode',
            'barcode',
            'backgrounds',
            'pageTitle'
        ))
            ->setPaper('A4')
            ->setOption('no-stop-slow-scripts', true)
            ->setOption('disable-smart-shrinking', true)
            ->setOption('enable-local-file-access', true)
            ->setOption('margin-top', 10)
            ->setOption('margin-bottom', 10)
            ->setOption('margin-left', 10)
            ->setOption('margin-right', 10);

        // 6. Stream PDF langsung ke browser
        return $pdf->stream('Kartu Pelajar' . '_' . $DownloadPDF->student->name . '.pdf');
    }

    public function downloadBulk(Request $request)
    {
        $kelas = $request->query('kelas');
        $jurusan = $request->query('jurusan');

        $cards = Card::with(['student.classroom', 'school', 'background'])
            ->where('status', 'active')
            ->whereHas('student.classroom', function ($query) use ($kelas, $jurusan) {
                $query->where('classroom', $kelas)
                    ->where('code_classroom', $jurusan);
            })
            ->get();

        if ($cards->isEmpty()) {
            return back()->with('error', 'Tidak ada kartu AKTIF untuk filter ' . $kelas . ' ' . $jurusan);
        }

        $pageTitle = 'Daftar Kartu Pelajar - ' . $jurusan;
        $combinedHtml = '';

        foreach ($cards as $card) {
            if (!$card->student) {
                continue;
            }

            $backgrounds = $card->background ?? Background::where('is_active', true)->first();

            $DownloadPDF = $card;

            $nisn = $DownloadPDF->student->nisn ?? '0000000000';
            $key_qrcode = $DownloadPDF->student->qrcode ?? 'data_tidak_tersedia';

            $generator = new \Picqer\Barcode\BarcodeGeneratorPNG();
            $barcode = base64_encode($generator->getBarcode($nisn, $generator::TYPE_CODE_128));

            $qrcode = \SimpleSoftwareIO\QrCode\Facades\QrCode::size(200)
                ->gradient(0, 100, 102, 0, 0, 0, 'vertical')
                ->generate($key_qrcode);

            $combinedHtml .= view('pages.cards.download.pdf-cards-bulk', compact('DownloadPDF', 'qrcode', 'barcode', 'backgrounds', 'pageTitle'))->render();
        }

        $pdf = \Barryvdh\Snappy\Facades\SnappyPdf::loadHTML($combinedHtml)
            ->setPaper('A4')
            ->setOption('enable-local-file-access', true)
            ->setOption('disable-smart-shrinking', true)
            ->setOption('margin-top', 3)
            ->setOption('margin-bottom', 0)
            ->setOption('margin-left', 10)
            ->setOption('margin-right', 10);

        // 6. Preview PDF di tab baru
        return $pdf->stream('Kartu Pelajar ' . $kelas . ' ' . $jurusan . '.pdf');
    }

    public function indexWakel(Request $request)
    {
        $user = auth()->user();

        $classroom = \App\Models\Classroom::where('user_id', $user->id)->first();

        if (!$classroom) {
            return redirect()->back()->with('error', 'Anda bukan wali kelas di kelas manapun.');
        }

        $query = \App\Models\Card::whereHas('student', function ($q) use ($classroom) {
            $q->where('classrooms_id', $classroom->id);
        });

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $query->whereHas('student', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            });
        }

        $perPage = $request->get('perPage', 5);

        $cards = $query->with(['student', 'background'])
            ->latest()
            ->paginate($perPage)
            ->withQueryString();

        $countActive = \App\Models\Card::whereHas('student', function ($q) use ($classroom) {
            $q->where('classrooms_id', $classroom->id);
        })->where('status', 'active')->count();

        $countExpired = \App\Models\Card::whereHas('student', function ($q) use ($classroom) {
            $q->where('classrooms_id', $classroom->id);
        })->where('status', 'expired')->count();

        return view('wali_kelas.cards.index', compact('cards', 'classroom', 'countActive', 'countExpired'));
    }

    public function WakelPDF($id)
    {
        $DownloadPDF = Card::with(['student.classroom', 'school', 'background'])->findOrFail($id);

        $backgrounds = $DownloadPDF->background ?? Background::where('is_active', true)->first();

        $pageTitle = $DownloadPDF->student->name;
        $nisn = $DownloadPDF->student->nisn ?? '0000000000';
        $key_qrcode = $DownloadPDF->student->qrcode ?? '0000000000';

        $generator = new \Picqer\Barcode\BarcodeGeneratorPNG();
        $barcode = base64_encode($generator->getBarcode($nisn, $generator::TYPE_CODE_128));

        $qrcode = \SimpleSoftwareIO\QrCode\Facades\QrCode::size(200)
            ->gradient(0, 100, 102, 0, 0, 0, 'vertical')
            ->generate($key_qrcode);

        $pdf = \Barryvdh\Snappy\Facades\SnappyPdf::loadView('wali_kelas.cards.download.pdf-cards', compact(
            'DownloadPDF',
            'qrcode',
            'barcode',
            'backgrounds',
            'pageTitle'
        ))
            ->setPaper('A4')
            ->setOption('no-stop-slow-scripts', true)
            ->setOption('disable-smart-shrinking', true)
            ->setOption('enable-local-file-access', true)
            ->setOption('margin-top', 100)
            ->setOption('margin-bottom', 10)
            ->setOption('margin-left', 5)
            ->setOption('margin-right', 10);

        // 6. Stream PDF langsung ke browser
        return $pdf->stream('Kartu Pelajar' . '_' . $DownloadPDF->student->name . '.pdf');
    }

    public function WakelBulk(Request $request)
    {
        $waliKelas = auth()->user();
        $classroom = $waliKelas->classroom;

        if (!$classroom) {
            return back()->with('error', 'Anda tidak terdaftar sebagai Wali Kelas di kelas manapun.');
        }

        $kelas = $classroom->classroom;
        $jurusan = $classroom->code_classroom;

        $cards = Card::with(['student.classroom', 'school', 'background'])
            ->where('status', 'active')
            ->whereHas('student.classroom', function ($query) use ($kelas, $jurusan) {
                $query->where('classroom', $kelas)
                    ->where('code_classroom', $jurusan);
            })
            ->get();

        if ($cards->isEmpty()) {
            return back()->with('error', 'Tidak ada kartu AKTIF di kelas ' . $kelas . ' ' . $jurusan);
        }

        $pageTitle = 'Daftar Kartu Pelajar - ' . $jurusan;
        $combinedHtml = '';

        foreach ($cards as $card) {
            if (!$card->student) continue;

            $backgrounds = $card->background ?? Background::where('is_active', true)->first();
            $DownloadPDF = $card;

            $nisn = $DownloadPDF->student->nisn ?? '0000000000';
            $key_qrcode = $DownloadPDF->student->qrcode ?? 'data_tidak_tersedia';

            $generator = new \Picqer\Barcode\BarcodeGeneratorPNG();
            $barcode = base64_encode($generator->getBarcode($nisn, $generator::TYPE_CODE_128));

            $qrcode = \SimpleSoftwareIO\QrCode\Facades\QrCode::size(200)
                ->gradient(0, 100, 102, 0, 0, 0, 'vertical')
                ->generate($key_qrcode);

            $combinedHtml .= view('wali_kelas.cards.download.pdf-cards-bulk', compact('DownloadPDF', 'qrcode', 'barcode', 'backgrounds', 'pageTitle'))->render();
        }

        // 4. Generate PDF
        $pdf = \Barryvdh\Snappy\Facades\SnappyPdf::loadHTML($combinedHtml)
            ->setPaper('A4')
            ->setOption('enable-local-file-access', true)
            ->setOption('disable-smart-shrinking', true)
            ->setOption('margin-top', 3)
            ->setOption('margin-bottom', 0)
            ->setOption('margin-left', 10)
            ->setOption('margin-right', 10);

        return $pdf->stream('Kartu Pelajar ' . $kelas . ' ' . $jurusan . '.pdf');
    }
}
