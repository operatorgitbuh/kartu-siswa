<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class School extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'nama_sekolah',
        'npsn_sekolah',
        'alamat_sekolah',
        'pemerintah_provinsi',
        'instansi_pemerintah',
        'email_sekolah',
        'website_sekolah',
        'nama_kepsek',
        'nip_kepsek',
        'logo_provinsi',
        'logo_sekolah',
        'ttd_kepsek',
        'cap_sekolah',
    ];
    public $incrementing = false;
    protected $keyType = 'string';

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
