<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class Credential extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'credentials';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'access_token',
        'refresh_token',
        'user_id',
    ];
    
}
