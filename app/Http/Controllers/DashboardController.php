<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
// use Illuminate\Pagination\LengthAwarePaginator;
// use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Ambil Data Background & Statistik Dasar
        $totalBackgrounds = \App\Models\Background::count();
        $activeBackground = \App\Models\Background::where('is_active', true)->first();
        $totalCards = \App\Models\Card::count();

        // 2. Ambil Riwayat Kartu (Paginate)
        $recentCards = \App\Models\Card::with(['student.classroom', 'school', 'background'])
            ->latest()
            ->paginate(5);

        // 3. Return ke View (Hanya data statistik & riwayat)
        return view('dashboard', [
            'totalStudents'        => \App\Models\Student::count(),
            'totalClassrooms'      => \App\Models\Classroom::count(),
            'totalUsers'           => \App\Models\User::count(),
            'totalBackgrounds'     => $totalBackgrounds,
            'activeBackgroundName' => $activeBackground?->name,
            'recentCards'          => $recentCards,
            'totalCards'           => $totalCards,
        ]);
    }

    public function indexWakel()
    {
        $myClass = \App\Models\Classroom::where('user_id', Auth::id())->first();

        if (!$myClass) {
            $studentsInClass = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 5);
            return view('wali_kelas.dashboard.index', [
                'totalStudentsInClass' => 0,
                'activeCardsInClass'   => 0,
                'expiredCardsInClass'  => 0,
                'uncreatedCardsCount'  => 0,
                'studentsInClass'      => $studentsInClass,
                'allStudents'          => collect([]),
            ])->with('error', 'Maaf, Anda belum ditugaskan sebagai Wali Kelas.');
        }

        $classId = $myClass->id;

        // 1. Total Siswa
        $totalStudentsInClass = \App\Models\Student::where('classrooms_id', $classId)->count();

        // 2. Kartu Aktif (Status aktif DAN tanggal belum lewat)
        $activeCardsInClass = \App\Models\Card::whereHas('student', function ($query) use ($classId) {
            $query->where('classrooms_id', $classId);
        })
            ->where('status', 'active')
            ->where('exp_date', '>=', now())
            ->count();

        // 3. Kartu Kadaluarsa (Status non-aktif ATAU tanggal sudah lewat)
        $expiredCardsInClass = \App\Models\Card::whereHas('student', function ($query) use ($classId) {
            $query->where('classrooms_id', $classId);
        })
            ->where(function ($q) {
                $q->where('status', '!=', 'active')
                    ->orWhere('exp_date', '<', now());
            })
            ->count();

        // 4. Belum Buat Kartu
        $uncreatedCardsCount = \App\Models\Student::where('classrooms_id', $classId)
            ->whereDoesntHave('card')
            ->count();

        // 5. Data Tabel (Hanya yang sudah punya kartu)
        $studentsInClass = \App\Models\Student::with(['card'])
            ->where('classrooms_id', $classId)
            ->has('card')
            ->latest()
            ->paginate(5);

        // 6. Data Modal (Semua untuk filter di Blade)
        $allStudents = \App\Models\Student::with(['card'])
            ->where('classrooms_id', $classId)
            ->orderBy('name', 'asc')
            ->get();

        return view('wali_kelas.dashboard.index', compact(
            'totalStudentsInClass',
            'activeCardsInClass',
            'expiredCardsInClass',
            'uncreatedCardsCount',
            'studentsInClass',
            'allStudents'
        ));
    }
}
