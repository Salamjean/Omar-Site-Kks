<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArticleFournisseur extends Model
{
    protected $fillable = [
        'name',
        'price',
        'description',
        'nombre',
        'categorie',
        'typeAccessoire',
        'other',
        'main_image',
        'hover_image',
        'total',
        'reduced',
        'status',
        'vendor_id',
    ];

    public function vendor()
{
    return $this->belongsTo(Vendor::class);
}
}
