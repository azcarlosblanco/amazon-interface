<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'orders';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'resource', 'topic',
        'order_id', 'status','product_id','nickname','name','email','phone','total_amount','paid_amount',
        'actual_nickname','actual_phone','shipping_details','observations','processed', 'rejected', 'cart_id'
    ];


    /**
     * Scope a query to only include not processed orders.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeNotprocessed($query)
    {
        return $query->where('processed', false);
    }

    /**
     * Scope a query to only include not processed orders.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeRejected($query)
    {
        return $query->where('rejected', true);
    }

    /**
    * Scope a query to only include users of a given type.
    *
    * @param \Illuminate\Database\Eloquent\Builder $query
    * @param mixed $asin
    * @return \Illuminate\Database\Eloquent\Builder
    */
   public function scopeFindByResource($query, $resource)
   {
       return $query->where('resource', $resource);

   }
}
