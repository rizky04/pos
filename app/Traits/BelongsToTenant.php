<?php

namespace App\Traits;

use App\Scopes\TenantScope;
use Illuminate\Support\Facades\Auth;

trait BelongsToTenant
{
    protected static function bootBelongsToTenant()
    {
        // Jika user superadmin (pakai spatie role), JANGAN pasang tenant scope
        if (Auth::check() && Auth::user()->hasRole('superadmin')) {
            return;
        }

        // Jika bukan superadmin → pasang tenant scope
        static::addGlobalScope(new TenantScope);

        // Saat create → isi tenant_id otomatis
        static::creating(function ($model) {
            if (Auth::check()) {
                $model->tenant_id = Auth::user()->tenant_id;
            }
        });
    }
}
