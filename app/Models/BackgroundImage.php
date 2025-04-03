<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BackgroundImage extends Model
{
    use HasFactory;

    /**
     * Les champs qui peuvent être assignés massivement.
     *
     * @var array<string>
     */
    protected $fillable = [
        'image_path', // Ajoutez ce champ
    ];
}
