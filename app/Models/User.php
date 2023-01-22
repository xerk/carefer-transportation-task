<?php

/**
 * App\Models\User
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string $type
 * @property string $remember_token
 * @property string $two_factor_secret
 * @property string $two_factor_recovery_codes
 * @property string $profile_photo_path
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property Bus[] $buses
 * @property Passenger[] $passengers
 * @property string $current_team_id
 */

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use App\Models\Scopes\Searchable;
use App\Models\Traits\FilamentTrait;
use Laravel\Jetstream\HasProfilePhoto;
use Illuminate\Notifications\Notifiable;
use Filament\Models\Contracts\FilamentUser;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 *
 */
class User extends Authenticatable implements FilamentUser
{
    use Notifiable;
    use HasFactory;
    use Searchable;
    use SoftDeletes;
    use HasApiTokens;
    use FilamentTrait;
    use HasProfilePhoto;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'email', 'password', 'type'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $searchableFields = ['*'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_secret',
        'two_factor_recovery_codes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'two_factor_confirmed_at' => 'datetime',
    ];

    /**
     * Get the bus associated with the user.
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function buses()
    {
        return $this->hasMany(Bus::class);
    }

    /**
     * Get the passenger associated with the user.
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function passengers()
    {
        return $this->hasMany(Passenger::class);
    }

    /**
     * Determine if the user is a super admin.
     *
     * @return bool
     */
    public function isSuperAdmin()
    {
        return in_array($this->email, config('auth.super_admins'));
    }
}
