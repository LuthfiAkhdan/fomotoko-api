<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'products';

    protected $primaryKey = 'id';

    protected $fillable = [
        'name',
        'price',
        'inventory',
    ];

    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    public function order_items()
    {
        return $this->hasMany('App\Models\OrderItem', 'product_id');
    }
}
