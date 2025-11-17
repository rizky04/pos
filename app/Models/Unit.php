<?php

namespace App\Models;
use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Unit extends Model
{
    /** @use HasFactory<\Database\Factories\UnitFactory> */
      use HasFactory, BelongsToTenant, HasUuids;

     protected $fillable = [
        'nama', 'kode', 'tipe', 'deskripsi', 'status', 'is_default', 'tenant_id'
    ];

      public $incrementing = false;
    protected $keyType = 'string';

     /**
     * Relasi ke tenant
     */
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
}
