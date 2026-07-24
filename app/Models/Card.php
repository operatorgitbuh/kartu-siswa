<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Card extends Model
{
    use HasUuids;
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'student_id',
        'school_id',
        'background_id',
        'foto',
        'status',
        'exp_date',
    ];

    // Relasi
    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }
    // public function classrooms()
    // {
    //     return $this->belongsTo(Classroom::class, 'student_id');
    // }
    public function school()
    {
        return $this->belongsTo(School::class);
    }
    public function background()
    {
        return $this->belongsTo(Background::class);
    }

    // Logic Otomatis: Jika lewat tanggal, status dianggap expired saat dipanggil
    public function getStatusAttribute($value)
    {
        if ($this->exp_date && Carbon::now()->gt(Carbon::parse($this->exp_date))) {
            return 'expired';
        }
        return $value;
    }
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = (string) \Illuminate\Support\Str::uuid();
            }
        });
    }
}
