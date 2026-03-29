<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_code', 'user_id', 'service_id', 'brand', 'device', 'price',
        'status', 'whatsapp', 'payment_proof', 'screenshot_imei', 'admin_note', 'processed_at'
    ];

    protected $casts = [
        'processed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function imeis()
    {
        return $this->hasMany(Imei::class);
    }
}
