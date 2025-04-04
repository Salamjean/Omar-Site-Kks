<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\BackgroundImageController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CommandeController;
use App\Http\Controllers\UserController;
use App\Models\BackgroundImage;
use Illuminate\Support\Facades\Route;

Route::get('/', [UserController::class, 'acceuil'])->name('user.accueil');
Route::middleware('auth')->prefix('shopping')->group(function(){
    Route::get('/space',[UserController::class,'space'])->name('user.space');
    Route::get('/shopping/new',[UserController::class,'new'])->name('user.new');
    Route::get('/shopping/clothes',[UserController::class,'clothes'])->name('user.clothes');
    Route::get('/shopping/shoes',[UserController::class,'shoes'])->name('user.shoes');
    Route::get('/shopping/accessory',[UserController::class,'accessory'])->name('user.accessory');
    Route::get('/logout',[UserController::class,'logout'])->name('user.logout');

    // Routes pour la commande
    Route::post('/commandes/{article}', [CommandeController::class, 'store'])->name('commandes.store');
    Route::get('/commandes/all-articles', [CommandeController::class, 'index'])->name('commandes.index');
    Route::post('/commandes/{commande}/update-quantity', [CommandeController::class, 'updateQuantity'])->name('commandes.updateQuantity');
    Route::post('/commandes/annuler/{commande}', [CommandeController::class, 'annuler'])->name('commandes.annuler');
});

Route::prefix('shopping')->group(function(){
    Route::get('/register',[UserController::class,'register'])->name('user.register');
    Route::post('/handleRegister',[UserController::class,'handleRegister'])->name('user.handleRegister');
    Route::get('/login',[UserController::class,'login'])->name('login');
    Route::post('/handleLogin',[UserController::class,'handleLogin'])->name('user.handleLogin');
});


Route::middleware('admin')->prefix('admin')->group(function () {
    Route::get('/admin/dashboard',[AdminController::class,'dashboard'])->name('admin.dashboard');
    Route::get('/admin/logout',[AdminController::class,'logout'])->name('admin.logout');
    Route::get('/add-article', [ArticleController::class, 'create'])->name('article.create');
    Route::post('/add-article', [ArticleController::class, 'storeArticle'])->name('article.store');
    Route::get('/all-article', [ArticleController::class, 'index'])->name('article.index');
    Route::get('/delete-article/{id}', [ArticleController::class, 'destroy'])->name('article.destroy');
    Route::get('/edit-article/{id}', [ArticleController::class, 'edit'])->name('article.edit');
    Route::put('/edit-article/{id}', [ArticleController::class, 'update'])->name('article.update');
    Route::resource('background_images', BackgroundImageController::class)->except(['show', 'edit', 'update']);

    //Routes des differents articles admin
    Route::get('/vêtements',[ArticleController::class, 'vetements'])->name('article.vetements');
    Route::get('/chaussures',[ArticleController::class, 'chaussures'])->name('article.chaussures');
    Route::get('/accessoires',[ArticleController::class, 'accessoires'])->name('article.accessoires');

    //Routes utilisateurs admin
    Route::get('users-lists',[UserController::class, 'UserList'])->name('users-lists');

    //Routes des commandes admin
    Route::get('/commandes/all-commandes', [CommandeController::class, 'allCommandes'])->name('commandes.allCommandes');
    Route::get('/commandes/all-commandes-effectuee', [CommandeController::class, 'effectuee'])->name('commandes.effectuee');
    Route::get('/commandes/{commande}/validate', [CommandeController::class, 'validate'])->name('commandes.validate');
    Route::get('/commandes/{commande}/cancel', [CommandeController::class, 'cancel'])->name('commandes.cancel');
    Route::put('/commandes/{commande}/update-status', [CommandeController::class, 'updateStatus'])->name('commandes.updateStatus');
    Route::put('/commandes/{commande}/update-payment-status', [CommandeController::class, 'updatePaymentStatus'])->name('commandes.updatePaymentStatus');
});

Route::get('/admin/register',[AdminController::class,'register'])->name('admin.register');
Route::post('/admin/handleRegister',[AdminController::class,'handleRegister'])->name('admin.handleRegister');
Route::get('/admin/login',[AdminController::class,'login'])->name('admin.login');
Route::post('/admin/handleLogin',[AdminController::class,'handleLogin'])->name('admin.handleLogin');
