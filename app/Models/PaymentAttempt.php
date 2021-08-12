<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentAttempt extends Model
{
    use HasFactory;

    /**
     * @var array
     */
    protected $fillable = [
        'external_id',
        'url_process',
        'state',
        'order_id'
    ];


    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
