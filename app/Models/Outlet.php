<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Outlet extends Model
{
    use HasUuids;

    protected $fillable = [
        'tenant_id',
        'outlet_name',
        'outlet_address',
        'city',
        'timezone',
    ];

    public $incrementing = false;
    protected $keyType = 'string';

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
}
