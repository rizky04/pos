<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Setting extends Model
{
    use HasUuids;

    protected $fillable = [
        'tenant_id',
        'default_tax',
        'prefix_sale',
        'prefix_purchase',
    ];

    public $incrementing = false;
    protected $keyType = 'string';

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
}
