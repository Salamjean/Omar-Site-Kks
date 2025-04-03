<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Commande extends Model
{
    protected $fillable =
     [
        'article_id',
        'article_name',
        'unit_price',
        'main_image',
        'categorie',
        'user_id',
        'quantity',
        'total_price',
        'status'
     ];

    public function article()
    {
        return $this->belongsTo(Article::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
