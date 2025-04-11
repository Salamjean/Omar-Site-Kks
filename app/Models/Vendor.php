<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Vendor extends Authenticatable
{
    protected $fillable =  [
        'userName',
        'name',
        'prenom',
        'password',
        'dateNaiss',
        'contact',
        'email',
        'commune',
        'role',
        'profile_pictures',
    ];

    public function articleFournisseurs()
    {
        return $this->hasMany(ArticleFournisseur::class);
    }
    public function articles()
    {
        return $this->hasMany(Article::class);
    }
}
