<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BuktiKepesertaanBpjs extends Model
{
    protected $table = 'bukti_kepesertaan_bpjs';

    protected $fillable = [
        'perusahaan_id',
        'kategori',
        'file',
        'is_active',
    ];

    protected $casts = [
        'is_active'  => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
