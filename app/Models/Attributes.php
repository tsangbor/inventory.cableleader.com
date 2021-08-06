<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attributes extends Model
{
    use HasFactory;
    protected $table = 'catalog_product_entity_varchar';
    protected $primaryKey = 'value_id';

    public function products()
    {
        return $this->belongsTo(Products::class, 'entity_id', 'entity_id');
    }
}
