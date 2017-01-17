<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class Alert extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'alerts';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'description', 'read', 'order'
    ];

    /**
     * Scope a query to only include not processed orders.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeNotRead($query)
    {
        return $query->where('read', false)->get();
    }
}
