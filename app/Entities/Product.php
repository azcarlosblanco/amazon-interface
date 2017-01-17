<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'products';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'amazon_id', 'ml_id', 'li_id', 'changed',
        'amazon_c', 'ml_p', 'li_p'
    ];

    /**
     * Get the detail record associated with the product.
     */
    public function detail()
    {
        return $this->hasOne('App\Entities\ProductDetail');
    }

    /**
    * Scope a query to only include users of a given type.
    *
    * @param \Illuminate\Database\Eloquent\Builder $query
    * @param mixed $asin
    * @return \Illuminate\Database\Eloquent\Builder
    */
   public function scopeIsAsin($query, $asin)
   {
       return $query->where('amazon_id', $asin);
   }

   /**
   * Scope a query to only include users of a given type.
   *
   * @param \Illuminate\Database\Eloquent\Builder $query
   * @param mixed $asin
   * @return \Illuminate\Database\Eloquent\Builder
   */
  public function scopeIsAsinForLinio($query, $asin)
  {
      return $query->where('amazon_id', $asin)
                   ->where('li_p', 1);
  }

   /**
   * Scope a query to only include users of a given type.
   *
   * @param \Illuminate\Database\Eloquent\Builder $query
   * @param mixed $asin
   * @return \Illuminate\Database\Eloquent\Builder
   */
  public function scopeIsMeliId($query, $meli_id)
  {
      return $query->where('ml_id', $meli_id);
  }

  /**
  * Scope a query to only include users of a given type.
  *
  * @param \Illuminate\Database\Eloquent\Builder $query
  * @param mixed $asin
  * @return \Illuminate\Database\Eloquent\Builder
  */
 public function scopeFindByPublish($query, $published)
 {
     if ($published == "meli") {
           return $query->where('ml_p', true);
     } elseif ($published == "linio") {
         return $query->where('li_p', true);
     } elseif ($published == "both") {
         return $query->where([
             'ml_p' => true,
             'li_p' => true
         ]);
     } elseif ($published == "finished") {
         return $query->where([
             'ml_p' => false,
             'li_p' => false
         ]);
     }
 }

}
