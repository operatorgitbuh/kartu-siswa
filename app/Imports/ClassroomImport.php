<?php

namespace App\Imports;

use App\Models\Classroom;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
// use Illuminate\Support\Str;

class ClassroomImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new Classroom([
            // Sesuaikan kiri (database) => kanan (header excel)
            'classroom'      => $row['tingkat'],    
            'code_classroom' => $row['kode'],       
            'name_classroom' => $row['nama_kelas'], 
        ]);
    }
}
