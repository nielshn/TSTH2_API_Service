<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Cache;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasRoles, HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone_number',
        'avatar',
        'password',
        'role_id',
    ];


    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function gudangs()
    {
        return $this->belongsToMany(Gudang::class, 'user_id');
    }



    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'created_at' => 'datetime:Y-m-d H:m:s',
            'updated_at' => 'datetime:Y-m-d H:m:s',
            'deleted_at' => 'datetime:Y-m-d H:m:s',
        ];
    }
   public function routeNotificationForMail($notification)
    {
        $pendingEmail = Cache::get('pending_email_' . $this->id);
        if ($pendingEmail) {
            return $pendingEmail;
        }
        return $this->email;
    }
}
