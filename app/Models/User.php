<?php

namespace App\Models;

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles, BelongsToTenant;

    protected $fillable = ['name', 'email', 'password', 'id_pengguna', 'tenant_id'];
    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function pengguna()
    {
        return $this->belongsTo(Pengguna::class, 'id_pengguna', 'id_pengguna');
    }

    public function tenant()
{
    return $this->belongsTo(Tenant::class);
}



}
