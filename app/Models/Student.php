<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Student extends Model
{
    use HasFactory, HasUuids;

    protected $guarded = [];

    public $incrementing = false;
    protected $keyType = 'string';

    public const AGAMA = ['Islam', 'Kristen', 'Katholik', 'Hindu', 'Budha', 'Khonghucu', 'Lainnya'];
    public const STATUS = ['active', 'non-active', 'lulus'];
    public const GENDER = ['Laki - Laki', 'Perempuan'];

    public function classroom()
    {
        return $this->belongsTo(Classroom::class, 'classrooms_id');
    }
    public function card(): HasOne
    {
        return $this->hasOne(Card::class, 'student_id');
    }
}
