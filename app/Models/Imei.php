<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Imei extends Model
{
    use HasFactory;

    protected $fillable = ['order_id', 'imei'];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
