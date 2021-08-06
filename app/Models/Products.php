<?php

namespace App\Models;

use Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
    use HasFactory;
    protected $table = 'catalog_product_entity';
    protected $primaryKey = 'entity_id';

    public function attributes()
    {
        return $this->hasMany(Attributes::class, 'entity_id', 'entity_id');
    }
}
