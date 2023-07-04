<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Notifications\EmailVerificationNotification;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Permission\Models\Role;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    # email verification notification
    public function sendVerificationNotification()
    {
        $this->notify(new EmailVerificationNotification());
    }

    # guarded
    protected $guarded = [
        ''
    ];

    # hidden for serializations
    protected $hidden = [
        'password',
        'remember_token',
    ];

    # should be casted
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    # role
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    # subscriptionPackage 
    public function subscriptionPackage()
    {
        return $this->belongsTo(SubscriptionPackage::class);
    }

    # referred users
    public function referredUsers()
    {
        return $this->hasMany(User::class, 'referred_by', 'id');
    }

    # referred users earnings
    public function referredUserEarnings()
    {
        return $this->hasMany(AffiliateEarning::class, 'referred_by', 'id');
    }

    # affiliatePayoutAccounts
    public function affiliatePayoutAccounts()
    {
        return $this->hasMany(AffiliatePayoutAccount::class);
    }
}
