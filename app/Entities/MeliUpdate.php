<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class MeliUpdate extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'meli_updates';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'meli_id',
    ];}
