<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\ArticleFournisseur;
use App\Models\BackgroundImage;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PersonnelController extends Controller
{
    public function dashboard(){
         // Récupère le vendeur connecté
         $vendor = Auth::guard('vendor')->user();
        
         // Vérifie si le vendeur est connecté (sécurité)
         if (!$vendor) {
             return redirect()->route('vendor.login')->with('error', 'Veuillez vous connecter');
         }

        $vetementsCount = Article::where('vendor_id', $vendor->id)->where('categorie', 'Vêtement')->count();
        $chaussureCount = Article::where('vendor_id', $vendor->id)->where('categorie', 'Chaussure')->count();
        $accessoireCount = Article::where('vendor_id', $vendor->id)->where('categorie', 'Accessoire')->count();
        $total = $vetementsCount + $accessoireCount + $chaussureCount;
        return view('admin.vendeur.dashboard',compact('vetementsCount', 'chaussureCount', 'accessoireCount','total'));
    }

    public function index()
    {
        $images = BackgroundImage::all();
        return view('admin.background_images.indexPersonnel', compact('images'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ],[
            'image.required' => 'Please select an image.',
            'image.image' => 'The file must be an image.',
            'image.mimes' => 'The image must be a file of type: jpeg, png, jpg, gif, svg, avif.',
            'image.max' => 'The image must be a file of type: jpeg, png, jpg, gif, svg, avif and size less than 2MB.',
        ]);

        $imagePath = $request->file('image')->store('background_images', 'public');

        BackgroundImage::create([
            'image_path' => $imagePath,
        ]);

        return redirect()->route('background_images_personnel.index')->with('success', 'Image uploaded successfully.');
    }

    public function destroy(BackgroundImage $backgroundImage)
    {
        Storage::disk('public')->delete($backgroundImage->image_path);
        $backgroundImage->delete();

        return redirect()->route('background_images_personnel.index')->with('success', 'Image deleted successfully.');
    }
    public function indexArricle()
    {
        $vendor = Auth::guard('vendor')->user();
        $articles = Article::where('vendor_id', $vendor->id)->paginate(8);
        return view('admin.vendeur.articles.index', compact('articles'));
    }

    public function create()
    {
        return view('admin.vendeur.articles.create');
    }

public function storeArticle(Request $request)
    {
        // Validation des données
        $request->validate([
            'product-name' => 'required|string',
            'product-price' => 'required|numeric',
            'nombre' => 'required|numeric',
            'categorie' => 'required',
            'description' => 'required|string',
            'main-image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:51200',
            'hover-image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:51200',
        ], [
            'product-name.required' => 'Veuillez entrer le nom du vêtement.',
            'product-price.required' => 'Veuillez entrer le prix du vêtement.',
            'main-image.required' => 'Veuillez sélectionner une image de face.',
            'main-image.image' => 'Le fichier doit être une image.',
            'nombre.required' => 'Veuillez entrer le nombre de vêtement.',
            'nombre.numeric' => 'Le nombre de vêtement doit être un nombre.',
            'categorie' => 'La catégotie est obligatoiore',
            'description.required' => 'Veuillez entrer une description.',
            'main-image.mimes' => 'L\'image face doit être de type : jpeg, png, jpg, gif, svg.',
            'main-image.max' => 'L\'image face ne doit pas dépasser 2 Mo.',
            'hover-image.required' => 'Veuillez sélectionner une image de dos.',
            'hover-image.image' => 'Le fichier doit être une image.',
            'hover-image.mimes' => 'L\'image de dos doit être de type : jpeg, png, jpg, gif, svg.',
            'hover-image.max' => 'L\'image de dos ne doit pas dépasser 2 Mo.',
        ]);
    
        try {
                // Récupération du vendeur connecté
            $vendor = Auth::guard('vendor')->user();
            
            if (!$vendor) {
                return back()->withErrors(['error' => 'Vendeur non authentifié']);
            }
            // Enregistrement des images dans le dossier public/images
            $mainImagePath = $request->file('main-image')->store('images', 'public');
            $hoverImagePath = $request->file('hover-image')->store('images', 'public');
    
            // Enregistrement dans la base de données
            $article = new Article();
            $article->name = $request->input('product-name');
            $article->price = $request->input('product-price');
            $article->nombre = $request->input('nombre');
            $article->categorie = $request->input('categorie');
            $article->other = $request->input('other');
            $article->typeAccessoire = $request->input('typeAccessoire');
            $article->description = $request->input('description');
            $article->main_image = $mainImagePath; // On enregistre le chemin relatif
            $article->hover_image = $hoverImagePath; // On enregistre le chemin relatif
            $article->vendor_id = $vendor->id; // ⭐ Attribution de l'ID du vendeur
            $article->save();
    
            return redirect()->route('personnel.article.index')->with('success', 'Article ajouté avec succès!');
        } catch (Exception $e) {
            return back()->withErrors(['error' => 'Une erreur est survenue lors de l\'ajout de l\'article.']);
        }
    }

    public function destroyArricle($id)
    {
        try {
            $article = Article::findOrFail($id);
            Storage::disk('public')->delete($article->main_image);
            Storage::disk('public')->delete($article->hover_image);
            $article->delete();
            return redirect()->route('personnel.article.index')->with('success', 'Article supprimé avec succès!');
        } catch (Exception $e) {
            return back()->withErrors(['error' => 'Une erreur est survenue lors de la suppression de l\'article.']);
        }
    }

    public function editArricle($id)
    {
        $article = Article::findOrFail($id);
        return view('admin.vendeur.articles.edit', compact('article'));
    }

    public function updateArricle(Request $request, $id)
    {
        try {
            $article = Article::findOrFail($id);
            $article->name = $request->input('product-name');
            $article->price = $request->input('product-price');
            $article->nombre = $request->input('nombre');
            $article->categorie = $request->input('categorie');
            $article->description = $request->input('description');
    
            // Gestion des champs optionnels
            if ($request->input('categorie') === 'Accessoire') {
                $article->typeAccessoire = $request->input('typeAccessoire');
                
                if ($request->input('typeAccessoire') === 'Autre') {
                    $article->other = $request->input('other');
                } else {
                    $article->other = null;
                }
            } else {
                // Si la catégorie n'est pas Accessoire, on vide les champs
                $article->typeAccessoire = null;
                $article->other = null;
            }
    
            // Gestion des images
            if ($request->hasFile('main-image')) {
                Storage::disk('public')->delete($article->main_image);
                $mainImagePath = $request->file('main-image')->store('images', 'public');
                $article->main_image = $mainImagePath;
            }
            
            if ($request->hasFile('hover-image')) {
                Storage::disk('public')->delete($article->hover_image);
                $hoverImagePath = $request->file('hover-image')->store('images', 'public');
                $article->hover_image = $hoverImagePath;
            }
    
            $article->save();
            return redirect()->route('personnel.article.index')->with('success', 'Article modifié avec succès!');
        } catch (Exception $e) {
            return back()->withErrors(['error' => 'Une erreur est survenue lors de la modification de l\'article.']);
        }
    }

        public function vetements()
        {
             // Récupère le vendeur connecté
            $vendor = Auth::guard('vendor')->user();
            // Récupérer les articles de catégorie "Vêtement"
            $articles = Article::where('vendor_id', $vendor->id)->where('categorie', 'Vêtement')->paginate(8);
            return view('admin.vendeur.articles.vetements', compact('articles'));
        }
    
        public function chaussures(){
            $vendor = Auth::guard('vendor')->user();
            $articles = Article::where('vendor_id', $vendor->id)->where('categorie', 'Chaussure')->paginate(8);
            return view('admin.vendeur.articles.chaussures',compact('articles'));
        }
    
        public function accessoires(){
            $vendor = Auth::guard('vendor')->user();
            $articles = Article::where('vendor_id', $vendor->id)->where('categorie', 'Accessoire')->paginate(8);
            return view('admin.vendeur.articles.accessoires',compact('articles'));
        }

        public function partanaire(){
            $articles = ArticleFournisseur::where('status', 'Envoyé')->paginate(8);
            return view('admin.vendeur.articles.fournisseur',compact('articles'));
        }

        public function publish(ArticleFournisseur $article_fournisseur)
{
    try {
        // Créer un nouvel article dans la table articles
        $article = new Article();
        $article->name = $article_fournisseur->name;
        $article->price = $article_fournisseur->price;
        $article->nombre = $article_fournisseur->nombre;
        $article->categorie = $article_fournisseur->categorie;
        $article->typeAccessoire = $article_fournisseur->typeAccessoire;
        $article->other = $article_fournisseur->other;
        $article->description = $article_fournisseur->description;
        $article->main_image = $article_fournisseur->main_image;
        $article->hover_image = $article_fournisseur->hover_image;
        $article->save();

        // Mettre à jour le statut dans article_fournisseurs
        $article_fournisseur->status = 'Publié';
        $article_fournisseur->save();

        // Supprimer l'article de la table article_fournisseurs (optionnel)
        // $article_fournisseur->delete();

        return redirect()->back()->with('success', 'Article publié avec succès!');
    } catch (Exception $e) {
        return back()->withErrors(['error' => 'Une erreur est survenue lors de la publication de l\'article.']);
    }
}

public function partenairePublier(){
    $articles = ArticleFournisseur::where('status', 'Publié')->paginate(8);
    return view('admin.vendeur.articles.partenairePublier', compact('articles'));
}

public function partanaireRefuser(){
    $articles = ArticleFournisseur::where('status', 'Refusé')->paginate(8);
    return view('admin.vendeur.articles.partenaireRefuser', compact('articles'));
}
}
