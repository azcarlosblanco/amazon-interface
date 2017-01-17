<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class ProductDetail extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'product_details';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'img_url', 'img_set', 'brand', 'departament', 'feature', 'offer', 'weight', 'price'
    ];

    /**
     * Get the product that owns the details.
     */
    public function product()
    {
        return $this->belongsTo('App\Entities\Product');
    }

}
