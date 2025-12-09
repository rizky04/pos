<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;

class PurchasePayment extends Model
{
    use HasFactory, BelongsToTenant, HasUuids, SoftDeletes;

    protected $fillable = [
        'tenant_id',
        'purchase_id',
        'payment_date',
        'payment_method',
        'reference',
        'note',
        'amount',
        'remaining_amount',
    ];

    public $incrementing = false;
    protected $keyType = 'string';

    public function purchase()
    {
        return $this->belongsTo(Purchase::class);
    }
}
