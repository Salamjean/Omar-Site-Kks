<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Commande;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PagesController extends Controller
{
    public function new()
    {
        
        $twoDaysAgo = now()->subDays(2);
        $query = Article::where('created_at', '>=', $twoDaysAgo);
        
        $categories = Article::distinct()->pluck('categorie');
        
        if(request()->has('categories') && !request()->input('all_categories', false)) {
            $selectedCategories = request()->input('categories');
            $query->whereIn('categorie', $selectedCategories);
        }
        
        $newItems = $query->get();
        
        return view('pages.pages.new', compact('newItems', 'categories'));
    }

    public function clothes(){
        $clothes = Article::where('categorie', 'Vêtement')->get();
        return view('pages.pages.clothes',compact('clothes'));
    }

    public function shoes(){
        $shoes = Article::where('categorie', 'Chaussure')->get();
        return view('pages.pages.shoes',compact('shoes'));
    }

    public function accessory(){
        $accessorys = Article::where('categorie', 'Accessoire')->get();
        return view('pages.pages.accessory',compact('accessorys'));
    }
}
