<?php

namespace App\Models;

namespace App\Models;
use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Warehouse extends Model
{
    use HasFactory, BelongsToTenant, HasUuids;

    protected $fillable = [
        'tenant_id',
        'code',
        'name',
        'type',
        'city',
        'address',
        'pic',
        'phone',
        'status',
        'is_default',
    ];


    public $incrementing = false;
    protected $keyType = 'string';

    // Jika menggunakan trait multi-tenant
    // use \App\Traits\BelongsToTenant;

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
}
