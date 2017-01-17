<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class AmazonSearch extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'amazon_searches';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'search', 'page', 'total_pages',
        'keywords','category', 'prime', 'node',
        'child', 'meli', 'linio'
    ];

}
