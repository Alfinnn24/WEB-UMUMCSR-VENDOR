<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PeraturanPerusahaan extends Model
{
    protected $table = 'peraturan_perusahaan';

    protected $fillable = [
        'perusahaan_id',
        'jenis',
        'nomor',
        'tanggal',
        'file',
        'is_active',
    ];

    protected $casts = [
        'tanggal'    => 'date',
        'is_active'  => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
