<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Commande;
use App\Models\CommandeEffectuee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CommandeController extends Controller
{
    public function index(){
        $user = Auth::user();
        // Compter les articles  commandés par l'utilisateur
        $articleCount = Commande::where('user_id', $user->id)
                          ->where('status', '!=', 'annulée')
                          ->count();
    
    // Récupérer les commandes (sauf annulées)
    $commandes = Commande::with('article')
                       ->where('user_id', Auth::id())
                       ->where('status', '!=', 'annulée')
                       ->paginate(9);
        return view('commandes.index', compact('commandes','articleCount'));
    }

    public function store(Request $request, Article $article)
{
    if (!Auth::check()) {
        return response()->json([
            'success' => false,
            'message' => 'Veuillez vous connecter pour commander.'
        ], 401);
    }

    try {
        $validated = $request->validate([
            'quantity' => [
                'required',
                'integer',
                'min:1',
                function ($attribute, $value, $fail) use ($article) {
                    if ($value > $article->nombre) {
                        $fail('La quantité demandée dépasse le stock disponible.');
                    }
                },
            ],
        ]);

        DB::beginTransaction();

        // Décrémentation du stock
        $article->decrement('nombre', $validated['quantity']);

        // Création de la commande avec toutes les infos
        $commande = Commande::create([
            'article_id' => $article->id,
            'article_name' => $article->name,
            'unit_price' => $article->price,
            'main_image' => $article->main_image,
            'categorie' => $article->categorie,
            'user_id' => Auth::id(),
            'quantity' => $validated['quantity'],
            'total_price' => $article->price * $validated['quantity'],
            'status' => 'En attente'
        ]);

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'Commande ajouté au panier',
            'commande' => $commande
        ]);

    } catch (\Illuminate\Validation\ValidationException $e) {
        DB::rollBack();
        return response()->json([
            'success' => false,
            'message' => $e->getMessage(),
            'errors' => $e->errors()
        ], 422);
        
    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Erreur commande: '.$e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Une erreur technique est survenue.'
        ], 500);
    }
}

public function updateQuantity(Request $request, Commande $commande)
{
    if (!Auth::check()) {
        return response()->json([
            'success' => false,
            'message' => 'Authentification requise'
        ], 401);
    }

    try {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        DB::beginTransaction();

        $article = $commande->article;
        $oldQuantity = $commande->quantity;
        $newQuantity = $validated['quantity'];
        $quantityDifference = $newQuantity - $oldQuantity;

        // Vérification du stock si on augmente la quantité
        if ($quantityDifference > 0 && $article->nombre < $quantityDifference) {
            throw new \Exception('Stock insuffisant pour cette modification');
        }

        // Mise à jour du stock de l'article
        $article->increment('nombre', -$quantityDifference); // Diminue ou augmente le stock selon la différence

        // Mise à jour de la commande
        $commande->update([
            'quantity' => $newQuantity,
            'total_price' => $commande->unit_price * $newQuantity
        ]);

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'Commande mise à jour avec succès!',
            'new_quantity' => $newQuantity,
            'new_total' => $commande->unit_price * $newQuantity,
            'stock_remaining' => $article->fresh()->nombre
        ]);

    } catch (\Illuminate\Validation\ValidationException $e) {
        DB::rollBack();
        return response()->json([
            'success' => false,
            'message' => $e->getMessage(),
            'errors' => $e->errors()
        ], 422);
    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json([
            'success' => false,
            'message' => $e->getMessage()
        ], 500);
    }
}


public function annuler(Commande $commande, Request $request)
{
    // Vérifier que la commande peut être annulée
    if ($commande->status !== 'En attente') {
        return response()->json([
            'message' => 'Seules les commandes en attente peuvent être annulées'
        ], 422);
    }

    DB::beginTransaction();
    try {
        // Restituer le stock
        $article = Article::find($request->article_id);
        $article->increment('nombre', $request->quantity);

        // Mettre à jour le statut de la commande
        $commande->status = 'annulée';
        $commande->save();

        DB::commit();

        return response()->json([
            'message' => 'Commande annulée avec succès. Le stock a été restitué.'
        ]);
    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json([
            'message' => 'Une erreur est survenue lors de l\'annulation: ' . $e->getMessage()
        ], 500);
    }
}

// Les fonctions commandes coté admin
 public function allCommandes(){
    $commandes = Commande::with('article')
                         ->where('status', '!=', 'annulée')
                         ->where('status', '!=', 'Livrée')
                         ->where('status', '!=', 'Refusée')
                         ->orderBy('created_at', 'desc')
                         ->paginate(8);
    return view('commandes.indexAdmin', compact('commandes'));
 }
 public function personnelallCommandes(){
    $commandes = Commande::with('article')
                         ->where('status', '!=', 'annulée')
                         ->where('status', '!=', 'Livrée')
                         ->where('status', '!=', 'Refusée')
                         ->orderBy('created_at', 'desc')
                         ->paginate(8);
    return view('commandes.indexVendeur', compact('commandes'));
 }

 public function validate(Commande $commande)
 {
     // Vérifier si la commande est en attente
     if (!in_array($commande->status, ['En attente', 'En cours'])) {
        return back()->with('error', 'Seules les commandes en attente ou en cours peuvent être validées.');
    }
 
     // Mettre à jour le statut
     $commande->update(['status' => 'Livrée']);
 
     // Enregistrer dans la table commande_effectuees
     CommandeEffectuee::create([
         'commande_id' => $commande->id,
         'article_name' => $commande->article_name,
         'categorie' => $commande->categorie,
         'unit_price' => $commande->unit_price,
         'quantity' => $commande->quantity,
         'total_price' => $commande->total_price,
         'main_image' => $commande->main_image,
         'status' => 'Livrée',
         'validated_at' => now(),
     ]);
 
     return back()->with('success', 'Commande validée avec succès.');
 }
 public function personnelvalidate(Commande $commande)
 {
     // Vérifier si la commande est en attente
     if (!in_array($commande->status, ['En attente', 'En cours'])) {
        return back()->with('error', 'Seules les commandes en attente ou en cours peuvent être validées.');
    }
 
     // Mettre à jour le statut
     $commande->update(['status' => 'Livrée']);
 
     // Enregistrer dans la table commande_effectuees
     CommandeEffectuee::create([
         'commande_id' => $commande->id,
         'article_name' => $commande->article_name,
         'categorie' => $commande->categorie,
         'unit_price' => $commande->unit_price,
         'quantity' => $commande->quantity,
         'total_price' => $commande->total_price,
         'main_image' => $commande->main_image,
         'status' => 'Livrée',
         'validated_at' => now(),
     ]);
 
     return back()->with('success', 'Commande validée avec succès.');
 }
 
 public function updateStatus(Request $request, Commande $commande)
 {
     $validatedData = $request->validate([
         'status' => 'required|in:En attente,En cours,Livrée,Refusée',
     ]);

     DB::transaction(function () use ($commande, $validatedData) {
         // Si le statut passe à "Refusée", restituer le stock
         if ($validatedData['status'] === 'Refusée') {
             Article::where('id', $commande->article_id)
                 ->increment('nombre', $commande->quantity);
         }

         $commande->update($validatedData);

         if (in_array($validatedData['status'], ['Livrée', 'Refusée'])) {
             CommandeEffectuee::updateOrCreate(
                 ['commande_id' => $commande->id],
                 [
                     'article_id' => $commande->article_id,
                     'article_name' => $commande->article_name,
                     'categorie' => $commande->categorie,
                     'unit_price' => $commande->unit_price,
                     'quantity' => $commande->quantity,
                     'total_price' => $commande->total_price,
                     'main_image' => $commande->main_image,
                     'status' => $validatedData['status'],
                     'validated_at' => now(),
                     'stock_restored' => $validatedData['status'] === 'Refusée',
                 ]
             );
         }
     });

     return redirect()->back()->with('success', 'Statut mis à jour avec succès');
 }
 public function personnelupdateStatus(Request $request, Commande $commande)
 {
     $validatedData = $request->validate([
         'status' => 'required|in:En attente,En cours,Livrée,Refusée',
     ]);

     DB::transaction(function () use ($commande, $validatedData) {
         // Si le statut passe à "Refusée", restituer le stock
         if ($validatedData['status'] === 'Refusée') {
             Article::where('id', $commande->article_id)
                 ->increment('nombre', $commande->quantity);
         }

         $commande->update($validatedData);

         if (in_array($validatedData['status'], ['Livrée', 'Refusée'])) {
             CommandeEffectuee::updateOrCreate(
                 ['commande_id' => $commande->id],
                 [
                     'article_id' => $commande->article_id,
                     'article_name' => $commande->article_name,
                     'categorie' => $commande->categorie,
                     'unit_price' => $commande->unit_price,
                     'quantity' => $commande->quantity,
                     'total_price' => $commande->total_price,
                     'main_image' => $commande->main_image,
                     'status' => $validatedData['status'],
                     'validated_at' => now(),
                     'stock_restored' => $validatedData['status'] === 'Refusée',
                 ]
             );
         }
     });

     return redirect()->back()->with('success', 'Statut mis à jour avec succès');
 }

public function cancel(Commande $commande)
{
    if (!in_array($commande->status, ['En attente', 'En cours'])) {
        return back()->with('error', 'Seules les commandes en attente ou en cours peuvent être refusées.');
    }

    DB::transaction(function () use ($commande) {
        // Restituer le stock
        Article::where('id', $commande->article_id)
            ->increment('nombre', $commande->quantity);

        // Mettre à jour le statut
        $commande->update(['status' => 'Refusée']);

        // Enregistrer dans l'historique
        CommandeEffectuee::create([
            'commande_id' => $commande->id,
            'article_id' => $commande->article_id,
            'article_name' => $commande->article_name,
            'categorie' => $commande->categorie,
            'unit_price' => $commande->unit_price,
            'quantity' => $commande->quantity,
            'total_price' => $commande->total_price,
            'main_image' => $commande->main_image,
            'status' => 'Refusée',
            'validated_at' => now(),
            'stock_restored' => true,
        ]);
    });

    return back()->with('success', 'Commande refusée et stock restitué avec succès.');
}
public function personnelcancel(Commande $commande)
{
    if (!in_array($commande->status, ['En attente', 'En cours'])) {
        return back()->with('error', 'Seules les commandes en attente ou en cours peuvent être refusées.');
    }

    DB::transaction(function () use ($commande) {
        // Restituer le stock
        Article::where('id', $commande->article_id)
            ->increment('nombre', $commande->quantity);

        // Mettre à jour le statut
        $commande->update(['status' => 'Refusée']);

        // Enregistrer dans l'historique
        CommandeEffectuee::create([
            'commande_id' => $commande->id,
            'article_id' => $commande->article_id,
            'article_name' => $commande->article_name,
            'categorie' => $commande->categorie,
            'unit_price' => $commande->unit_price,
            'quantity' => $commande->quantity,
            'total_price' => $commande->total_price,
            'main_image' => $commande->main_image,
            'status' => 'Refusée',
            'validated_at' => now(),
            'stock_restored' => true,
        ]);
    });

    return back()->with('success', 'Commande refusée et stock restitué avec succès.');
}

 public function effectuee(){
    $commandes = CommandeEffectuee::where('status', '!=', 'En cours')
                ->where('status', '!=', 'En attente')
                ->paginate(8);
    return view('commandes.indexEffectuee', compact('commandes'));
 }
 public function personneleffectuee(){
    $commandes = CommandeEffectuee::where('status', '!=', 'En cours')
                ->where('status', '!=', 'En attente')
                ->paginate(8);
    return view('commandes.indexVendeurEffectuee', compact('commandes'));
 }
}