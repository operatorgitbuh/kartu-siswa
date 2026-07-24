<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classroom extends Model
{
    use HasFactory, HasUuids;

    public const JURUSAN = [
        'Akuntansi dan Keuangan Lembaga',
        'Agribisnis Tanaman',
        'Agribisnis Ternak',
        'Desain Pemodelan Dan Informasi Bangunan',
        'Teknik Jaringan Komputer dan Telekomunikasi',
        'Agribisnis Tanaman Pangan dan Hortikultura',
        'Agribisnis Tanaman Perkebunan',
        'Agribisnis Ternak Ruminansia',
        'Agribisnis Ternak Unggas',
        'Teknik Komputer dan Jaringan',
    ];
    protected $fillable = [
        'classroom',
        'code_classroom',
        'name_classroom',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function classroom()
    {
        return $this->belongsTo(Classroom::class, 'classroom_id');
    }
    public function students()
    {
        return $this->hasMany(Student::class, 'classrooms_id');
    }
}
