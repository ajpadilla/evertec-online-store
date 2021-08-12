<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'price'
    ];


    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * @param $query
     * @param $value
     * @return mixed
     */
    public function scopeOfPrice($query, $value)
    {
        if (is_array($value) && !empty($value)) {
            return $query->whereIn('products.price', $value);
        }

        return !$value ? $query : $query->where('products.price', $value);
    }
}
