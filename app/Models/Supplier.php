<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Supplier extends Model
{
    use HasFactory, SoftDeletes, BelongsToTenant, HasUuids;

    protected $fillable = [
        'tenant_id',
        'kode_supplier',
        'nama_supplier',
        'contact_person',
        'telepon',
        'email',
        'alamat',
        'kota',
        'termin_pembayaran',
        'npwp',
        'status',
    ];

      public $incrementing = false;
    protected $keyType = 'string';

    protected $casts = [
        'termin_pembayaran' => 'integer',
    ];

    /**
     * Relasi ke tenant
     */
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * Relasi ke pembelian (jika nanti ada)
     */
    public function pembelians()
    {
        return $this->hasMany(Pembelian::class, 'id_supplier', 'id');
    }

    /**
     * Scope untuk filter status
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeNonactive($query)
    {
        return $query->where('status', 'nonactive');
    }

    /**
     * Accessor untuk format termin
     */
    public function getTerminFormatAttribute()
    {
        if ($this->termin_pembayaran == 0) {
            return 'COD / Cash';
        }
        return $this->termin_pembayaran . ' Hari';
    }
}
