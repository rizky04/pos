<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Tenant extends Model
{
    use HasUuids;

    protected $fillable = [
        'name',
        'owner_name',
        'email',
        'phone',
        'package',
    ];

    public $incrementing = false;
    protected $keyType = 'string';

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function outlets()
    {
        return $this->hasMany(Outlet::class);
    }

    public function settings()
    {
        return $this->hasOne(Setting::class);
    }
}
