<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;


class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
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


    // relation one to many to withdrawal
    public function withdrawals()
    {
        return $this->hasMany(Withdrawal::class, 'user_id');
    }


    // relation one to one relation with wallet
    public function wallet()
    {
        return $this->belongsTo(Wallet::class, 'user_id');
    }


    // relation one to many relation with investment
    public function investments()
    {
        return $this->hasMany(Investment::class, 'user_id');
    }
}