<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, BelongsToTenant, HasUuids, SoftDeletes;

    protected $fillable = [
        'tenant_id',
        'category_id',
        'unit_id',
        'kode',
        'nama',
        'harga_modal',
        'harga_jual',
        'stok',
        'status',
    ];

    public $incrementing = false;
    protected $keyType = 'string';

    /** RELASI */
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    /** SCOPE */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeNonactive($query)
    {
        return $query->where('status', 'nonactive');
    }
}
