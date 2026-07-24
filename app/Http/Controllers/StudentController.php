<?php

namespace App\Http\Controllers;

use App\Imports\StudentsImport;
use App\Models\Classroom;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->get('perPage', 5);
        $search = $request->get('search');
        $classroomId = $request->get('classroom_id'); // Ambil filter kelas
        $status = $request->get('status', 'active');

        $students = Student::with('classroom')
            ->when($search, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('nisn', 'like', "%{$search}%");
                });
            })
            ->when($classroomId, function ($query, $classroomId) {
                return $query->where('classrooms_id', $classroomId); // Filter berdasarkan ID kelas
            })
            ->where('status', $status)
            ->orderBy('name', 'asc')
            ->paginate($perPage)
            ->withQueryString();

        $classrooms = Classroom::orderBy('classroom', 'asc')->get();

        return view('pages.students.index', compact('students', 'classrooms'));
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
