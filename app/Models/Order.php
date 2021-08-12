<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    /** @var array  */
    protected $fillable = [
        'customer_name',
        'customer_last_name',
        'customer_email',
        'customer_mobile',
        'customer_document_number',
        'customer_document_type',
        'amount',
        'status',
        'user_id',
        'product_id'
    ];


    protected $dates = [
        'created_at',
        'updated_at'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function paymentAttempts()
    {
        return $this->hasMany(PaymentAttempt::class);
    }

    public function getFirstPaymentAttempt()
    {
        return $this->paymentAttempts()->count() ? $this->paymentAttempts()->first() : null;
    }

    public function getFirstPaymentAttemptState()
    {
        return $this->paymentAttempts()->count() ? $this->paymentAttempts()->first()->state : null;
    }

    public function getFirstPaymentAttemptUrlProcess()
    {
        return $this->paymentAttempts()->count() ? $this->paymentAttempts()->first()->url_process : null;
    }

    public function getProductName()
    {
        return $this->product ?  $this->product->name : null;
    }

    public function getProductPrice()
    {
        return $this->product ?  $this->product->price : null;
    }

    public function getTotalProducts()
    {
        return $this->product()->count();
    }

    public function hasProducts(){
        return $this->getTotalProducts() > 0;
    }

}
