<?php

namespace App\Imports;

// use App\Models\Classroom;
// use App\Models\Student;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
// use Illuminate\Support\Str;
use Carbon\Carbon;

class StudentsImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        if (empty(trim($row['nisn'] ?? '')) && empty(trim($row['nama'] ?? ''))) {
            return null;
        }

        $inputKelas = trim($row['class_id'] ?? '');

        if ($inputKelas === '') {
            throw new \Exception("Gagal Import: Kolom 'class_id' kosong pada siswa: " . ($row['nama'] ?? 'Tanpa Nama'));
        }

        $classroom = \App\Models\Classroom::whereRaw("CONCAT(classroom, ' ', code_classroom) = ?", [$inputKelas])
            ->orWhere('classroom', $inputKelas)
            ->orWhere('code_classroom', $inputKelas)
            ->first();

        if (!$classroom) {
            throw new \Exception("Gagal Import: Kelas '{$inputKelas}' tidak ditemukan di database SMKN 1 Wonosari.");
        }

        $jk = trim(strtoupper($row['jk'] ?? ''));
        if ($jk === 'L') {
            $jkFinal = 'Laki - Laki';
        } elseif ($jk === 'P') {
            $jkFinal = 'Perempuan';
        } else {
            $jkFinal = $row['jk'] ?? '-';
        }

        try {
            $tglInput = $row['tgl_lahir'] ?? null;
            if (is_numeric($tglInput)) {
                $tglLahir = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($tglInput)->format('Y-m-d');
            } else {
                $tglLahir = \Carbon\Carbon::parse(str_replace(['/', ' '], '-', $tglInput))->format('Y-m-d');
            }
        } catch (\Exception $e) {
            throw new \Exception("Format tanggal salah pada siswa: " . ($row['nama'] ?? 'Unknown'));
        }

        return new \App\Models\Student([
            'id'            => (string) \Illuminate\Support\Str::uuid(),
            'nisn'          => trim($row['nisn']),
            'nipd'          => $row['nipd'] ?? null,
            'qrcode'        => isset($row['qrcode']) ? trim($row['qrcode']) : null,
            'name'          => trim($row['nama']),
            'classrooms_id' => $classroom->id,
            'jenis_kelamin' => $jkFinal,
            'agama'         => trim($row['agama'] ?? 'Islam'),
            'tempat_lahir'  => trim($row['tempat_lahir'] ?? '-'),
            'tanggal_lahir' => $tglLahir,
            'status'        => 'active',
        ]);
    }
}
