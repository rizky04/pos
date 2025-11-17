<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use HasFactory, SoftDeletes, BelongsToTenant, HasUuids;

    protected $fillable = [
        'tenant_id',
        'kode',
        'nama',
        'telepon',
        'email',
        'tipe',
        'member',
        'alamat',
        'kota',
        'limit',
        'status',
        'catatan',
    ];

    public $incrementing = false;
    protected $keyType = 'string';

    protected $casts = [
        'limit' => 'decimal:2',
    ];

    /**
     * Relasi ke tenant
     */
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
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
}
