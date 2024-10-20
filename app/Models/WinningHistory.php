<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WinningHistory extends Model
{
    protected $fillable = [
        'user_id',
        'vmm_id',
        'coins',
    ];



    // relation one to one relation with user
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }


    // relation one to one relation with vmm
    public function vmm()
    {
        return $this->belongsTo(VMM::class, 'vmm_id');
    }
}