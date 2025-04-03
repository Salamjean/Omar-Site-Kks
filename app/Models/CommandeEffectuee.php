<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommandeEffectuee extends Model
{
    use HasFactory;

    protected $fillable = [
        'commande_id',
        'article_name',
        'categorie',
        'unit_price',
        'quantity',
        'total_price',
        'main_image',
        'status',
        'validated_at'
    ];

    public function commande()
    {
        return $this->belongsTo(Commande::class);
    }
}