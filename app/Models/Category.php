<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    /** @use HasFactory<\Database\Factories\CategoryFactory> */
          use HasFactory, BelongsToTenant, HasUuids;

      protected $fillable = [
        'tenant_id',
        'kode',
        'nama',
        'status',
    ];
       public $incrementing = false;
    protected $keyType = 'string';

      public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeNonactive($query)
    {
        return $query->where('status', 'nonactive');
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
    public function products()
{
    return $this->hasMany(Product::class);
}
}
