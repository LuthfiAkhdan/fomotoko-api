<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'orders';

    protected $primaryKey = 'id';

    protected $fillable = [
        'buyer_name',
        'status',
    ];

    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    public function order_items()
    {
        return $this->hasMany('App\Models\OrderItem', 'order_id');
    }
}
