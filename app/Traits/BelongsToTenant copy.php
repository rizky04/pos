<?php

namespace App\Traits;

use App\Scopes\TenantScope;
use Illuminate\Support\Facades\Auth;

trait BelongsToTenant
{
    protected static function bootBelongsToTenant()
    {
        static::addGlobalScope(new TenantScope);

        static::creating(function ($model) {
            if (Auth::check()) {
                $model->tenant_id = Auth::user()->tenant_id;
            }
        });
    }
}
