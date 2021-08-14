<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

    /**
     * @return BelongsTo
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return HasMany
     */
    public function paymentAttempts(): HasMany
    {
        return $this->hasMany(PaymentAttempt::class);
    }

    /**
     * @return Model|null
     */
    public function getFirstPaymentAttempt(): ?Model
    {
        return $this->paymentAttempts()->count() ? $this->paymentAttempts()->first() : null;
    }

    /**
     * @return string|null
     */
    public function getFirstPaymentAttemptState(): ?string
    {
        return $this->paymentAttempts()->count() ? $this->paymentAttempts()->first()->state : null;
    }

    /**
     * @return string|null
     */
    public function getFirstPaymentAttemptUrlProcess(): ?string
    {
        return $this->paymentAttempts()->count() ? $this->paymentAttempts()->first()->url_process : null;
    }

    /**
     * @return string|null
     */
    public function getProductName(): ?string
    {
        return $this->product ?  $this->product->name : null;
    }

    /**
     * @return float|null
     */
    public function getProductPrice(): ?float
    {
        return $this->product ?  $this->product->price : null;
    }

    /**
     * @return int
     */
    public function getTotalProducts(): int
    {
        return $this->product()->count();
    }

    /**
     * @return bool
     */
    public function hasProducts(): bool
    {
        return $this->getTotalProducts() > 0;
    }

}
