<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use HasFactory, BelongsToTenant, HasUuids, SoftDeletes;

    protected $fillable = [
        'tenant_id',
        'user_id',
        'kode',
        'sub_total',
        'discount_value',
        'discount_type',
        'total_after_discount',
        'ppn',
        'total_after_ppn',
        'pay_amount',
        'change_amount',
        'status',
    ];

    public $incrementing = false;
    protected $keyType = 'string';

    public function items()
    {
        return $this->hasMany(TransactionItem::class);
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
}
