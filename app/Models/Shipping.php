<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shipping extends Model
{
    use HasFactory;

    protected $table="shippings";

    public function shipping(){
        return $this->belongsTo(Shipping::class);
    }
}
