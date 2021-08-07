<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shooting extends Model
{
    protected $guarded = [];
    use HasFactory;

    function team() {
        return $this->belongsTo(App\Team::class);
    }
}
