<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComtopInventory extends Model
{
    use HasFactory;
    protected $table = 'comtopinventory_stock_item';
    protected $primaryKey = 'item_id';

    protected $fillable = [
        'sku',
        'cl_productid',
        'source_qty',
        'qty',
    ];
}
