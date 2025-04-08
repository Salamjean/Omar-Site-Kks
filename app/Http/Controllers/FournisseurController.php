<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ArticleFournisseur;
use Exception;
use Illuminate\Http\Request;

class FournisseurController extends Controller
{
    public function dashboard(){
        return view('fournisseur.dashboard');
    }

    public function index(){
        $articles = ArticleFournisseur::paginate(8);
        return view('fournisseur.articles.index',compact('articles'));
    }

    public function addArticle(){
        return view('fournisseur.articles.create');
    }

    public function storeArticle(Request $request)
        {
            // Validation des données
            $request->validate([
                'name' => 'required|string',
                'price' => 'required|numeric',
                'nombre' => 'required|numeric',
                'categorie' => 'required',
                'description' => 'required|string',
                'main_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:51200',
                'hover_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:51200',
            ], [
                'name.required' => 'Veuillez entrer le nom du vêtement.',
                'price.required' => 'Veuillez entrer le prix du vêtement.',
                'main_image.required' => 'Veuillez sélectionner une image de face.',
                'main_image.image' => 'Le fichier doit être une image.',
                'nombre.required' => 'Veuillez entrer le nombre de vêtement.',
                'nombre.numeric' => 'Le nombre de vêtement doit être un nombre.',
                'categorie' => 'La catégotie est obligatoiore',
                'description.required' => 'Veuillez entrer une description.',
                'main_image.mimes' => 'L\'image face doit être de type : jpeg, png, jpg, gif, svg.',
                'main_image.max' => 'L\'image face ne doit pas dépasser 2 Mo.',
                'hover_image.required' => 'Veuillez sélectionner une image de dos.',
                'hover_image.image' => 'Le fichier doit être une image.',
                'hover_image.mimes' => 'L\'image de dos doit être de type : jpeg, png, jpg, gif, svg.',
                'hover_image.max' => 'L\'image de dos ne doit pas dépasser 2 Mo.',
            ]);
        
            try {
                // Enregistrement des images dans le dossier public/images
                $mainImagePath = $request->file('main_image')->store('images', 'public');
                $hoverImagePath = $request->file('hover_image')->store('images', 'public');
        
                // Enregistrement dans la base de données
                $article = new ArticleFournisseur();
                $article->name = $request->name;
                $article->price = $request->price;
                $article->nombre = $request->nombre;
                $article->categorie = $request->categorie;
                $article->other = $request->other;
                $article->typeAccessoire = $request->typeAccessoire;
                $article->description = $request->description;
                $article->total = $request->total;
                $article->reduced = $request->reduced;
                $article->main_image = $mainImagePath; // On enregistre le chemin relatif
                $article->hover_image = $hoverImagePath; // On enregistre le chemin relatif
                $article->save();
        
                return redirect()->route('fournisseur.index')->with('success', 'Article ajouté avec succès!');
            } catch (Exception $e) {
                return back()->withErrors(['error' => 'Une erreur est survenue lors de l\'ajout de l\'article.']);
            }
        }

public function vetements()
{
    // Récupérer les articles de catégorie "Vêtement"
    $articles = ArticleFournisseur::where('categorie', 'Vêtement')->paginate(8);
    return view('fournisseur.articles.vetements', compact('articles'));
}

    public function chaussures(){
        $articles = ArticleFournisseur::where('categorie', 'Chaussure')->paginate(8);
        return view('fournisseur.articles.chaussures',compact('articles'));
    }

    public function accessoires(){
        $articles = ArticleFournisseur::where('categorie', 'Accessoire')->paginate(8);
        return view('fournisseur.articles.accessoires',compact('articles'));
    }
}
