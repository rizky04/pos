<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;

class Purchase extends Model
{
    use HasFactory, BelongsToTenant, HasUuids, SoftDeletes;

    protected $fillable = [
        'tenant_id',
        'supplier_id',
        'kode',
        'invoice',
        'tanggal',
        'jatuh_tempo',
        'status_pembelian',
        'metode_bayar',
        'catatan',
        'ppn_percent',
        'discount_transaction',
        'subtotal',
        'total_ppn',
        'grand_total',
    ];

    public $incrementing = false;
    protected $keyType = 'string';

    /** RELASI */
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function items()
    {
        return $this->hasMany(PurchaseItem::class);
    }

    public function purchaseItems()
    {
        return $this->hasMany(PurchaseItem::class);
    }
    public function payments()
{
    return $this->hasMany(PurchasePayment::class);
}


}
