<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class Parameter extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'parameters';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'TRM', 'tax_usa', 'iva_co', 'costo_envio_kg', 'default_weight', 'utilidad', 'comision_meli', 'comision_linio'
    ];
}
