<?php

namespace App\Http\Controllers;

use App\Imports\StudentsImport;
use App\Models\Classroom;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;

class StudentController extends Controller
{
    // public function index(Request $request)
    // {
    //     $perPage = $request->get('perPage', 5);
    //     $search = $request->get('search');
    //     $classroomId = $request->get('classroom_id'); // Ambil filter kelas
    //     $status = $request->get('status', 'active');

    //     $students = Student::with('classroom')
    //         ->when($search, function ($query, $search) {
    //             return $query->where(function ($q) use ($search) {
    //                 $q->where('name', 'like', "%{$search}%")
    //                     ->orWhere('nisn', 'like', "%{$search}%");
    //             });
    //         })
    //         ->when($classroomId, function ($query, $classroomId) {
    //             return $query->where('classrooms_id', $classroomId); // Filter berdasarkan ID kelas
    //         })
    //         ->where('status', $status)
    //         ->orderBy('name', 'asc')
    //         ->paginate($perPage)
    //         ->withQueryString();

    //     $classrooms = Classroom::orderBy('classroom', 'asc')->get();

    //     return view('pages.students.index', compact('students', 'classrooms'));
    // }

    public function index(Request $request)
{
    $perPage = $request->get('perPage', 5);
    $search = $request->get('search');
    $classroomId = $request->get('classroom_id'); 
    $status = $request->get('status', 'active');

    $students = Student::with('classroom')
        ->when($search, function ($query, $search) {
            return $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('nisn', 'like', "%{$search}%");
            });
        })
        ->when($classroomId, function ($query, $classroomId) {
            return $query->where('classrooms_id', $classroomId); 
        })
        ->where('status', $status)
        ->orderBy('name', 'asc')
        ->paginate($perPage)
        ->withQueryString();

    $classrooms = Classroom::orderBy('classroom', 'asc')->get();
    $kelass = $classrooms; // <-- Tambahkan baris ini

    return view('pages.students.index', compact('students', 'classrooms', 'kelass')); // <-- Tambahkan 'kelass'
}
    public function imports(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv'
        ]);

        Excel::import(new StudentsImport, $request->file('file'));

        return back()->with('success', 'Data siswa berhasil diimport, Bro!');
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'           => 'required|string|max:255',
            'nisn'           => 'required|string|max:10|unique:students,nisn',
            'nipd'           => 'nullable|string|max:20',
            'qrcode'         => 'nullable|string|max:255',
            'classrooms_id'  => 'required|exists:classrooms,id',
            'jenis_kelamin'  => ['required', Rule::in(Student::GENDER)],
            'agama'          => ['required', Rule::in(Student::AGAMA)],
            'status'         => ['required', Rule::in(Student::STATUS)],
            'tempat_lahir'   => 'required|string|max:100',
            'tanggal_lahir'  => 'required|date',
        ]);

        Student::create($validated);

        return redirect()->back()->with('success', 'Data siswa berhasil ditambahkan.');
    }

    public function update(Request $request, Student $student)
    {
        $validated = $request->validate([
            'name'           => 'required|string|max:255',
            'nisn'           => ['required', 'string', 'max:10', Rule::unique('students')->ignore($student->id)],
            'nipd'           => 'nullable|string|max:20',
            'qrcode'         => 'nullable|string|max:255',
            'classrooms_id'  => 'required|exists:classrooms,id',
            'jenis_kelamin'  => ['required', Rule::in(Student::GENDER)],
            'agama'          => ['required', Rule::in(Student::AGAMA)],
            'status'         => ['required', Rule::in(Student::STATUS)],
            'tempat_lahir'   => 'required|string|max:100',
            'tanggal_lahir'  => 'required|date',
        ]);

        $student->update($validated);

        return redirect()->back()->with('success', 'Data siswa berhasil diperbarui.');
    }

    public function destroy(Student $student)
    {
        $student->delete();

        return redirect()->back()->with('success', 'Data siswa berhasil dihapus.');
    }

// public function naikKelas(Request $request)
// {
//     DB::beginTransaction();

//     try {
//         // 1. UBAH STATUS SISWA KELAS XII MENJADI 'lulus'
//         $kelasXII_ids = Classroom::whereIn('classroom', ['XII', '12'])->pluck('id');
//         $lulusCount = Student::whereIn('classrooms_id', $kelasXII_ids)
//             ->where('status', 'active')
//             ->update(['status' => 'lulus']);


//         // 2. NAIKKAN SISWA KELAS XI -> XII (Langsung Otomatis Karena Jurusan Sudah Sama)
//         $kelasXI = Classroom::whereIn('classroom', ['XI', '11'])->get();
//         $xiToXiiCount = 0;

//         foreach ($kelasXI as $kelasAsal) {
//             $kelasTujuan = Classroom::whereIn('classroom', ['XII', '12'])
//                 ->where('code_classroom', $kelasAsal->code_classroom)
//                 ->first();

//             if ($kelasTujuan) {
//                 $xiToXiiCount += Student::where('classrooms_id', $kelasAsal->id)
//                     ->where('status', 'active')
//                     ->update(['classrooms_id' => $kelasTujuan->id]);
//             }
//         }


//         // 3. NAIKKAN SISWA KELAS X -> XI (KECUALI Jurusan Yang Butuh Ploting)
//         // Pemetaan Jurusan Otomatis (Non-Ploting)
//         $jurusanAutoMap = [
//             'AKL'  => 'AKL',
//             'DPIB' => 'DPIB',
//             'TJKT' => 'TKJ',
//         ];

//         $kelasX = Classroom::whereIn('classroom', ['X', '10'])->get();
//         $xToXiCount = 0;

//         foreach ($kelasX as $kelasAsal) {
//             $kodeAsal = trim($kelasAsal->code_classroom);

//             // JIKA JURUSAN ATN / ATK -> LEWATI (Akan di-plot manual)
//             if (in_array($kodeAsal, ['ATN', 'ATK'])) {
//                 continue; 
//             }

//             // Jika jurusan terdaftar di AutoMap
//             if (isset($jurusanAutoMap[$kodeAsal])) {
//                 $targetCode = $jurusanAutoMap[$kodeAsal];

//                 $kelasTujuan = Classroom::whereIn('classroom', ['XI', '11'])
//                     ->where('code_classroom', $targetCode)
//                     ->first();

//                 if ($kelasTujuan) {
//                     $xToXiCount += Student::where('classrooms_id', $kelasAsal->id)
//                         ->where('status', 'active')
//                         ->update(['classrooms_id' => $kelasTujuan->id]);
//                 }
//             }
//         }

//         DB::commit();

//         $total = $lulusCount + $xiToXiiCount + $xToXiCount;

//         return redirect()->back()->with(
//             'success', 
//             "Proses selesai! {$total} siswa berhasil diproses ({$lulusCount} Lulus, {$xiToXiiCount} Naik XII, {$xToXiCount} Naik XI). Catatan: Siswa kelas X ATN & ATK belum dipindahkan dan siap untuk di-ploting manual."
//         );

//     } catch (\Exception $e) {
//         DB::rollBack();
//         return redirect()->back()->with('error', 'Gagal memproses: ' . $e->getMessage());
//     }
// }

public function naikKelas(Request $request)
{
    DB::beginTransaction();

    try {
        // =========================================================================
        // SKENARIO 1: BULK PINDAH KELAS SPESIFIK (Berdasarkan Centang Checkbox)
        // =========================================================================
        if ($request->action_type === 'bulk_selected') {
            $request->validate([
                'student_ids' => 'required|array',
                'student_ids.*' => 'exists:students,id',
                'to_classroom_id' => 'required|exists:classrooms,id',
            ], [
                'student_ids.required' => 'Pilih setidaknya satu siswa untuk dipindahkan.',
                'to_classroom_id.required' => 'Pilih kelas tujuan pemindahan.',
            ]);

            $movedCount = Student::whereIn('id', $request->student_ids)
                ->update(['classrooms_id' => $request->to_classroom_id]);

            DB::commit();

            return redirect()->back()->with(
                'success', 
                "Berhasil memindahkan {$movedCount} siswa terpilih ke kelas tujuan."
            );
        }

        // =========================================================================
        // SKENARIO 2: KENAIKAN KELAS MASAL OTOMATIS (Satu Sekolah)
        // =========================================================================
        
        // 1. UBAH STATUS SISWA KELAS XII MENJADI 'lulus'
        $kelasXII_ids = Classroom::whereIn('classroom', ['XII', '12'])->pluck('id');
        $lulusCount = Student::whereIn('classrooms_id', $kelasXII_ids)
            ->where('status', 'active')
            ->update(['status' => 'lulus']);

        // 2. NAIKKAN SISWA KELAS XI -> XII (Langsung Otomatis Karena Jurusan Sudah Sama)
        $kelasXI = Classroom::whereIn('classroom', ['XI', '11'])->get();
        $xiToXiiCount = 0;

        foreach ($kelasXI as $kelasAsal) {
            $kelasTujuan = Classroom::whereIn('classroom', ['XII', '12'])
                ->where('code_classroom', $kelasAsal->code_classroom)
                ->first();

            if ($kelasTujuan) {
                $xiToXiiCount += Student::where('classrooms_id', $kelasAsal->id)
                    ->where('status', 'active')
                    ->update(['classrooms_id' => $kelasTujuan->id]);
            }
        }

        // 3. NAIKKAN SISWA KELAS X -> XI (KECUALI Jurusan Yang Butuh Ploting)
        // Pemetaan Jurusan Otomatis (Non-Ploting)
        $jurusanAutoMap = [
            'AKL'  => 'AKL',
            'DPIB' => 'DPIB',
            'TJKT' => 'TKJ',
        ];

        $kelasX = Classroom::whereIn('classroom', ['X', '10'])->get();
        $xToXiCount = 0;

        foreach ($kelasX as $kelasAsal) {
            $kodeAsal = trim($kelasAsal->code_classroom);

            // JIKA JURUSAN ATN / ATK -> LEWATI (Akan di-plot manual lewat Bulk Checkbox)
            if (in_array($kodeAsal, ['ATN', 'ATK'])) {
                continue; 
            }

            // Jika jurusan terdaftar di AutoMap
            if (isset($jurusanAutoMap[$kodeAsal])) {
                $targetCode = $jurusanAutoMap[$kodeAsal];

                $kelasTujuan = Classroom::whereIn('classroom', ['XI', '11'])
                    ->where('code_classroom', $targetCode)
                    ->first();

                if ($kelasTujuan) {
                    $xToXiCount += Student::where('classrooms_id', $kelasAsal->id)
                        ->where('status', 'active')
                        ->update(['classrooms_id' => $kelasTujuan->id]);
                }
            }
        }

        DB::commit();

        $total = $lulusCount + $xiToXiiCount + $xToXiCount;

        return redirect()->back()->with(
            'success', 
            "Proses selesai! {$total} siswa berhasil diproses ({$lulusCount} Lulus, {$xiToXiiCount} Naik XII, {$xToXiCount} Naik XI). Catatan: Siswa kelas X ATN & ATK belum dipindahkan dan siap untuk di-ploting manual."
        );

    } catch (\Exception $e) {
        DB::rollBack();
        return redirect()->back()->with('error', 'Gagal memproses: ' . $e->getMessage());
    }
}

// Method untuk memproses ploting siswa terpilih ke kelas XI tujuan
public function processPloting(Request $request)
{
    $request->validate([
        'target_classroom_id' => 'required|exists:classrooms,id',
        'student_ids' => 'required|array',
        'student_ids.*' => 'exists:students,id',
    ]);

    Student::whereIn('id', $request->student_ids)
        ->update(['classrooms_id' => $request->target_classroom_id]);

    return redirect()->back()->with('success', count($request->student_ids) . ' siswa berhasil di-ploting ke kelas tujuan!');
}



    public function indexWakel(Request $request)
    {
        $user = auth()->user();

        $classroom = \App\Models\Classroom::where('user_id', $user->id)->first();

        if (!$classroom) {
            $students = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 5);
            return view('wali_kelas.students.index', [
                'students' => $students,
                'classroom' => null,
                'classrooms' => []
            ]);
        }

        $query = \App\Models\Student::where('classrooms_id', $classroom->id);

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('nisn', 'like', '%' . $request->search . '%');
            });
        }

        $students = $query->orderBy('name', 'asc')
            ->paginate($request->get('perPage', 5))
            ->withQueryString();

        return view('wali_kelas.students.index', [
            'students'    => $students,
            'classroom'   => $classroom,
            'classrooms'  => [$classroom]
        ]);
    }

    public function updateWakel(Request $request, $id)
    {
        $student = Student::findOrFail($id);

        $validated = $request->validate([
            'name'           => 'required|string|max:255',
            'nisn'           => ['required', 'string', 'max:10', \Illuminate\Validation\Rule::unique('students')->ignore($student->id)],
            'nipd'           => 'nullable|string|max:20',
            'qrcode'         => 'nullable|string|max:255',
            'classrooms_id'  => 'required|exists:classrooms,id',
            'jenis_kelamin'  => ['required', \Illuminate\Validation\Rule::in(Student::GENDER)],
            'agama'          => ['required', \Illuminate\Validation\Rule::in(Student::AGAMA)],
            'status'         => ['required', \Illuminate\Validation\Rule::in(Student::STATUS)],
            'tempat_lahir'   => 'required|string|max:100',
            'tanggal_lahir'  => 'required|date',
        ]);

        $student->update($validated);

        return redirect()->back()->with('success', 'Data ' . $student->name . ' berhasil diperbarui.');
    }
}
