<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\BackgroundImageController;
use App\Http\Controllers\CommandeController;
use App\Http\Controllers\FournisseurController;
use App\Http\Controllers\PagesController;
use App\Http\Controllers\PersonnelController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VendorController;
use Illuminate\Support\Facades\Route;

Route::get('/', [UserController::class, 'acceuil'])->name('user.accueil');
Route::middleware('auth')->prefix('shopping')->group(function(){
    Route::get('/space',[UserController::class,'space'])->name('user.space');
    Route::get('/shopping/new',[UserController::class,'new'])->name('user.new');
    Route::get('/shopping/clothes',[UserController::class,'clothes'])->name('user.clothes');
    Route::get('/shopping/shoes',[UserController::class,'shoes'])->name('user.shoes');
    Route::get('/shopping/accessory',[UserController::class,'accessory'])->name('user.accessory');
    Route::get('/logout',[UserController::class,'logout'])->name('user.logout');
    Route::get('/search', [UserController::class, 'search'])->name('user.search');

    // Routes pour la commande
    Route::post('/commandes/{article}', [CommandeController::class, 'store'])->name('commandes.store');
    Route::get('/commandes/all-articles', [CommandeController::class, 'index'])->name('commandes.index');
    Route::post('/commandes/{commande}/update-quantity', [CommandeController::class, 'updateQuantity'])->name('commandes.updateQuantity');
    Route::post('/commandes/annuler/{commande}', [CommandeController::class, 'annuler'])->name('commandes.annuler');
    Route::post('/commandes/{id}/store-payment', [CommandeController::class, 'storePayment'])->name('commandes.storePayment');
});

Route::prefix('accueil')->name('user')->group(function(){
    Route::get('/shopping/new',[PagesController::class,'new'])->name('accueil.new');
    Route::get('/shopping/clothes',[PagesController::class,'clothes'])->name('accueil.clothes');
    Route::get('/shopping/shoes',[PagesController::class,'shoes'])->name('accueil.shoes');
    Route::get('/shopping/accessory',[PagesController::class,'accessory'])->name('accueil.accessory');
});

Route::prefix('shopping')->group(function(){
    Route::get('/register',[UserController::class,'register'])->name('user.register');
    Route::post('/handleRegister',[UserController::class,'handleRegister'])->name('user.handleRegister');
    Route::get('/login',[UserController::class,'login'])->name('login');
    Route::post('/handleLogin',[UserController::class,'handleLogin'])->name('user.handleLogin');
});


Route::middleware(\App\Http\Middleware\AdminMiddleware::class)->prefix('admin')->group(function () {
    Route::get('/dashboard',[AdminController::class,'dashboard'])->name('admin.dashboard');
    Route::get('/logout',[AdminController::class,'logout'])->name('admin.logout');
    Route::get('/add-article', [ArticleController::class, 'create'])->name('article.create');
    Route::post('/add-article', [ArticleController::class, 'storeArticle'])->name('article.store');
    Route::get('/all-article', [ArticleController::class, 'index'])->name('article.index');
    Route::get('/all/delete-article/{id}', [ArticleController::class, 'destroy'])->name('article.destroy');
    Route::put('/articles/reject/{id}', [FournisseurController::class, 'reject'])->name('article.reject');
    Route::get('/edit-article/{id}', [ArticleController::class, 'edit'])->name('article.edit');
    Route::put('/edit-article/{id}', [ArticleController::class, 'update'])->name('article.update');
    Route::resource('background_images', BackgroundImageController::class)->except(['show', 'edit', 'update']);

    //Routes des differents articles admin
    Route::get('/vêtements',[ArticleController::class, 'vetements'])->name('article.vetements');
    Route::get('/chaussures',[ArticleController::class, 'chaussures'])->name('article.chaussures');
    Route::get('/accessoires',[ArticleController::class, 'accessoires'])->name('article.accessoires');
    Route::post('/article/publish/{article_fournisseur}', [ArticleController::class, 'publish'])->name('article.publish');

    //routes du partenaire
    Route::get('partanaire',[ArticleController::class, 'partanaire'])->name('article.partanaire');
    Route::get('partanaire/publier',[ArticleController::class, 'partenairePublier'])->name('article.publierPartenaire');
    Route::get('partanaire/refuser',[ArticleController::class, 'partanaireRefuser'])->name('article.refuserPartenaire');
    //Route::delete('/partenaire/supprimer/{article}', [ArticleController::class, 'deleteArticle'])->name('article.destroy');

    //Routes utilisateurs admin
    Route::get('users-lists',[UserController::class, 'UserList'])->name('users-lists');
    Route::get('/vendor/{id}/edit', [VendorController::class, 'edit'])->name('vendor.edit');
    Route::put('/vendor/{id}', [VendorController::class, 'update'])->name('vendor.update');
    Route::delete('/vendor/{id}', [VendorController::class, 'destroy'])->name('vendor.destroy');

    //Routes des commandes admin
    Route::get('/commandes/all-commandes', [CommandeController::class, 'allCommandes'])->name('commandes.allCommandes');
    Route::get('/commandes/all-commandes-effectuee', [CommandeController::class, 'effectuee'])->name('commandes.effectuee');
    Route::get('/commandes/{commande}/validate', [CommandeController::class, 'validate'])->name('commandes.validate');
    Route::get('/commandes/{commande}/cancel', [CommandeController::class, 'cancel'])->name('commandes.cancel');
    Route::put('/commandes/{commande}/update-status', [CommandeController::class, 'updateStatus'])->name('commandes.updateStatus');
    Route::put('/commandes/{commande}/update-payment-status', [CommandeController::class, 'updatePaymentStatus'])->name('commandes.updatePaymentStatus');

    //Routes admin pour les vendeurs
    Route::get('/vendor/create',[VendorController::class,'create'])->name('vendor.create');
    Route::post('/vendor/create',[VendorController::class,'store'])->name('vendor.store');
    Route::get('/vendor/index',[VendorController::class,'index'])->name('vendor.index');

});

//routes de vendeurs 
Route::middleware(\App\Http\Middleware\VendorMiddleware::class)->prefix('vendor')->group(function(){
    //routes fournisseurs
    Route::get('/dashbaord/fournisseur',[FournisseurController::class,'dashboard'])->name('fournisseur.dashboard');
    Route::get('/logout/fournisseur',[FournisseurController::class,'logout'])->name('vendor.logout');
    Route::get('/fournisseur/create/article',[FournisseurController::class,'addArticle'])->name('fournisseur.addArticle');
    Route::post('/fournisseur/create/article',[FournisseurController::class,'storeArticle'])->name('fournisseur.storeArticle');
    Route::get('/fournisseur/index/article',[FournisseurController::class,'index'])->name('fournisseur.index');
    Route::get('/fournisseur/refuser/article',[FournisseurController::class,'refuser'])->name('fournisseur.refuser');
    Route::get('/vêtements',[FournisseurController::class, 'vetements'])->name('fournisseur.vetements');
    Route::get('/chaussures',[FournisseurController::class, 'chaussures'])->name('fournisseur.chaussures');
    Route::get('/accessoires',[FournisseurController::class, 'accessoires'])->name('fournisseur.accessoires');

    //routes personnels
    Route::resource('background_images_personnel', PersonnelController::class)->except(['show', 'edit', 'update']);
    Route::get('/dashbaord/personnel',[PersonnelController::class,'dashboard'])->name('vendor.dashboard');
    Route::get('/add-article', [PersonnelController::class, 'create'])->name('personnel.article.create');
    Route::post('/add-article', [PersonnelController::class, 'storeArticle'])->name('personnel.article.store');
    Route::get('/all-article', [PersonnelController::class, 'indexArricle'])->name('personnel.article.index');
    Route::get('/all/delete-article/{id}', [PersonnelController::class, 'destroyArricle'])->name('personnel.article.destroy');
    Route::get('/edit-article/{id}', [PersonnelController::class, 'editArricle'])->name('personnel.article.edit');
    Route::put('/edit-article/{id}', [PersonnelController::class, 'updateArricle'])->name('personnel.article.update');

    //Routes articles vendeur
    Route::get('all/vêtements',[PersonnelController::class, 'vetements'])->name('personnel.article.vetements');
    Route::get('all/chaussures',[PersonnelController::class, 'chaussures'])->name('personnel.article.chaussures');
    Route::get('all/accessoires',[PersonnelController::class, 'accessoires'])->name('personnel.article.accessoires');
    Route::post('/article/publish/{article_fournisseur}', [PersonnelController::class, 'publish'])->name('personnel.article.publish');

    //Routes partenaires vendeurs 
    Route::get('partanaire',[PersonnelController::class, 'partanaire'])->name('personnel.article.partanaire');
    Route::get('partanaire/publier',[PersonnelController::class, 'partenairePublier'])->name('personnel.article.publierPartenaire');
    Route::get('partanaire/refuser',[PersonnelController::class, 'partanaireRefuser'])->name('personnel.article.refuserPartenaire');

     //Routes des commandes vendeurs
     Route::get('/commandes/all-commandes', [CommandeController::class, 'personnelallCommandes'])->name('personnel.commandes.allCommandes');
     Route::get('/commandes/all-commandes-effectuee', [CommandeController::class, 'personneleffectuee'])->name('personnel.commandes.effectuee');
     Route::get('/commandes/{commande}/validate', [CommandeController::class, 'personnelvalidate'])->name('personnel.commandes.validate');
     Route::get('/commandes/{commande}/cancel', [CommandeController::class, 'personnelcancel'])->name('personnel.commandes.cancel');
     Route::put('/commandes/{commande}/update-status', [CommandeController::class, 'personnelupdateStatus'])->name('personnel.commandes.updateStatus');
     Route::put('/commandes/{commande}/update-payment-status', [CommandeController::class, 'updatePaymentStatus'])->name('personnel.commandes.updatePaymentStatus');
});

Route::get('/vendor/login',[VendorController::class,'login'])->name('vendor.login');
Route::post('/vendor/login',[VendorController::class,'handleLogin'])->name('vendor.handleLogin');
Route::get('/validate-vendor-account/{email}', [VendorController::class, 'defineAccess']);
Route::post('/validate-vendor-account/{email}', [VendorController::class, 'submitDefineAccess'])->name('vendor.validate');

Route::get('/admin/register',[AdminController::class,'register'])->name('admin.register');
Route::post('/admin/handleRegister',[AdminController::class,'handleRegister'])->name('admin.handleRegister');
Route::get('/admin/login',[AdminController::class,'login'])->name('admin.login');
Route::post('/admin/handleLogin',[AdminController::class,'handleLogin'])->name('admin.handleLogin');
