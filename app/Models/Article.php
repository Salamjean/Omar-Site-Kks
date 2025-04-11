<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $fillable = 
    ['title',
     'content',
     'image_path',
     'status',
     'vendor_id',
    ]; 

    public function commandes()
{
    return $this->hasMany(Commande::class);
}

public function vendor()
{
    return $this->belongsTo(Vendor::class);
}
}
