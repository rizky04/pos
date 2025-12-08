<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class PurchaseItem extends Model
{
    use HasFactory, BelongsToTenant, HasUuids;

    protected $fillable = [
        'tenant_id',
        'purchase_id',
        'product_id',
        'nama_barang',
        'discount_percent',
        'qty',
        'harga_beli',
        'subtotal',
    ];

    public $incrementing = false;
    protected $keyType = 'string';

    /** RELASI */
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function purchase()
    {
        return $this->belongsTo(Purchase::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
