<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $guarded = [];


    public function user()
    {
        return $this->belongsTo(User::class)->withDefault();
    }
    public function gateway()
    {
        return $this->belongsTo(Gateway::class)->withDefault();
    }
}
