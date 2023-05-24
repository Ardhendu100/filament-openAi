<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Filament\Models\Contracts\FilamentUser;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Venturecraft\Revisionable\RevisionableTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements FilamentUser
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;
    use RevisionableTrait;


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'api_key',
        'api_secret',
        'username'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The attributes that should be encrypted and decrypted automatically.
     *
     * @var array
     */
    protected $encrypts = ['api_secret'];

    /**
     * Set the user's API secret (encrypt it).
     *
     * @param  string  $value
     * @return void
     */
    public function setApiSecretAttribute($value)
    {
        $this->attributes['api_secret'] = encrypt($value);
    }

    /**
     * Get the user's API secret (decrypt it).
     *
     * @param  string  $value
     * @return string
     */
    public function getApiSecretAttribute($value)
    {
        return decrypt($value);
    }

    public function canAccessFilament(): bool
    {
        return $this->hasRole(['super-admin', 'api-user']);
    }
}
