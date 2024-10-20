<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'vmm_coin',
        'taka',
    ];



    // relation one to one relation with user
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}