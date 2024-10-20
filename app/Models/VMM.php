<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VMM extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'lifetime',
        'minimum_invest',
        'distribute_coin',
        'execution_time',
        'preparation_time',
        'start_time',
        'status',
    ];


    // relation one to one relation with investment
    public function invest()
    {
        return $this->belongsTo(Investment::class, 'vmm_id');
    }
}