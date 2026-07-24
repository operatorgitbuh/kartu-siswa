<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use App\Models\User;
use Illuminate\Http\Request;
use App\Imports\ClassroomImport;
use Maatwebsite\Excel\Facades\Excel;

class ClassroomController extends Controller
{
    public function index(Request $request)
{
    $perPage = $request->get('perPage', 5);

    $classrooms = Classroom::with(['user', 'students.card']) // Ambil relasi card
        ->withCount('students')
        ->orderBy('classroom', 'asc')
        ->paginate($perPage)
        ->appends(['perPage' => $perPage]);

    $users = User::all();
    $listJurusan = Classroom::JURUSAN;
    
    return view('pages.classrooms.index', compact('classrooms', 'users', 'listJurusan'));
}

    public function imports(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv'
        ]);

        try {
            Excel::import(new ClassroomImport, $request->file('file'));
            return back()->with('success', 'Data Kelas berhasil di-import!');
        } catch (\Exception $e) {
            return back()->with('error', 'Ada masalah pas import: ' . $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'classroom' => 'required|unique:classrooms,code_classroom',
            'code_classroom' => 'required',
            'name_classroom' => 'required',
            'user_id' => 'nullable|exists:users,id',
        ]);

        Classroom::create($data);
        return back()->with('success', 'Data Kelas berhasil ditambahkan!');
    }

    public function update(Request $request, Classroom $classroom)
    {
        $data = $request->validate([
            // Sekarang 'classroom' yang unik, tapi abaikan ID yang sedang diupdate
            'classroom'      => 'required',
            'code_classroom' => 'required',
            'name_classroom' => 'required',
            'user_id'        => 'nullable|exists:users,id',
        ]);

        $classroom->update($data);

        return back()->with('success', 'Data Kelas berhasil diperbarui!');
    }

    public function destroy(Classroom $classroom)
    {
        $classroom->delete();
        return back()->with('success', 'Data Kelas berhasil dihapus!');
    }
}
