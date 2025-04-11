<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ArticleFournisseur;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FournisseurController extends Controller
{
    public function dashboard()
    {
        // Récupère le vendeur connecté
        $vendor = Auth::guard('vendor')->user();
        
        // Vérifie si le vendeur est connecté (sécurité)
        if (!$vendor) {
            return redirect()->route('vendor.login')->with('error', 'Veuillez vous connecter');
        }
        // Compteurs filtrés par vendor_id
        $vetementsCount = ArticleFournisseur::where('vendor_id', $vendor->id)->where('categorie', 'Vêtement')->count();
        $chaussureCount = ArticleFournisseur::where('vendor_id', $vendor->id)->where('categorie', 'Chaussure') ->count();
        $accessoireCount = ArticleFournisseur::where('vendor_id', $vendor->id)->where('categorie', 'Accessoire')->count();

        // Compteurs pour le status (filtrés par vendor)
        $accessoireCountPublier = ArticleFournisseur::where('vendor_id', $vendor->id)->where('status', 'Publié') ->count();
        $accessoireCountRefuser = ArticleFournisseur::where('vendor_id', $vendor->id)->where('status', 'Refusé') ->count();
    
        // Total des articles du vendeur (toutes catégories)
        $total = $vetementsCount + $chaussureCount + $accessoireCount;
    
        return view('fournisseur.dashboard', compact(
            'vetementsCount',
            'chaussureCount',
            'accessoireCount',
            'total',
            'accessoireCountPublier',
            'accessoireCountRefuser'
        ));
    }

    public function index()
{
    $vendor = Auth::guard('vendor')->user();
    $articles = ArticleFournisseur::where('vendor_id', $vendor->id)->where('status', '!=', 'Refusé')->paginate(8);
    return view('fournisseur.articles.index', compact('articles'));
}

    public function addArticle(){
        return view('fournisseur.articles.create');
    }

    public function storeArticle(Request $request)
{
    // Validation des données (inchangée)
    $request->validate([
        'name' => 'required|string',
        'price' => 'required|numeric',
        'nombre' => 'required|numeric',
        'categorie' => 'required',
        'description' => 'required|string',
        'main_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:51200',
        'hover_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:51200',
    ], [
        // Messages d'erreur (inchangés)
    ]);

    try {
        // Enregistrement des images
        $mainImagePath = $request->file('main_image')->store('images', 'public');
        $hoverImagePath = $request->file('hover_image')->store('images', 'public');

        // Création de l'article AVEC le vendor_id
        $article = ArticleFournisseur::create([
            'name' => $request->name,
            'price' => $request->price,
            'nombre' => $request->nombre,
            'categorie' => $request->categorie,
            'description' => $request->description,
            'main_image' => $mainImagePath,
            'hover_image' => $hoverImagePath,
            'total' => $request->total,
            'reduced' => $request->reduced,
            'typeAccessoire' => $request->typeAccessoire,
            'other' => $request->other,
            'vendor_id' => Auth::guard('vendor')->id(), // Récupère l'ID du vendeur connecté
        ]);

        return redirect()->route('fournisseur.index')->with('success', 'Article ajouté avec succès!');
    } catch (Exception $e) {
        return back()->withErrors(['error' => 'Une erreur est survenue: ' . $e->getMessage()]);
    }
}

public function vetements()
{
    $vendor = Auth::guard('vendor')->user();
    // Récupérer les articles de catégorie "Vêtement"
    $articles = ArticleFournisseur::where('vendor_id', $vendor->id)->where('categorie', 'Vêtement')->paginate(8);
    return view('fournisseur.articles.vetements', compact('articles'));
}

    public function chaussures(){
        $vendor = Auth::guard('vendor')->user();
        $articles = ArticleFournisseur::where('vendor_id', $vendor->id)->where('categorie', 'Chaussure')->paginate(8);
        return view('fournisseur.articles.chaussures',compact('articles'));
    }

    public function accessoires(){
        $vendor = Auth::guard('vendor')->user();
        $articles = ArticleFournisseur::where('vendor_id', $vendor->id)->where('categorie', 'Accessoire')->paginate(8);
        return view('fournisseur.articles.accessoires',compact('articles'));
    }
    public function reject($id)
    {
        $article = ArticleFournisseur::findOrFail($id);
        $article->status = 'Refusé';
        $article->save(); 
    
        return back()->with('success', 'Statut mis à jour');
    }

    public function refuser()
    {
        $vendor = Auth::guard('vendor')->user();
        $articles = ArticleFournisseur::where('vendor_id', $vendor->id)->where('status', 'Refusé')->paginate(8);
        return view('fournisseur.articles.refuser', compact('articles'));
    }

    public function logout(){
        auth()->guard('vendor')->logout();
        return redirect()->route('vendor.login');
    }
}
