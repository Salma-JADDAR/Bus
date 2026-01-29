<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CityController;



/*
|--------------------------------------------------------------------------
| Route simple (page d'accueil)
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
})->name('homepage');

/*
Route::get('/cities', [CityController::class, 'index']);
*/
/*
|--------------------------------------------------------------------------
| Exemple de routes simples (sans resource)
|--------------------------------------------------------------------------
*/


Route::get('/hello', function () {
   return view('hello');
});
Route::get('/cities', function () {
     return 'liste des villes';
 });    




// Exemple : /user/5
Route::get('/user/{id}', function ($id) {
    return "User ID : " . $id;
});

// Redirection (2 façons)

// Avec redirect()
Route::get('/salam', function () {
    return redirect('/hello');
});

// Avec Route::redirect (plus court)
Route::redirect('/bonjour', '/hello');

/*
|--------------------------------------------------------------------------
| Exemple CRUD sans resource (manuel)
|--------------------------------------------------------------------------
| Ici on écrit chaque route à la main
*/

//Route::get('/cities', [CityController::class, 'index']);       // afficher la liste
//Route::get('/cities/create', [CityController::class, 'create']); // formulaire
//Route::post('/cities', [CityController::class, 'store']);      // sauvegarder
//Route::get('/cities/{id}', [CityController::class, 'show']);   // afficher une ville
//Route::get('/cities/{id}/edit', [CityController::class, 'edit']); // modifier
//Route::put('/cities/{id}', [CityController::class, 'update']); // mettre à jour
//Route::delete('/cities/{id}', [CityController::class, 'destroy']); // supprimer

/*
|--------------------------------------------------------------------------
| Exemple CRUD avec resource
|--------------------------------------------------------------------------
| Laravel crée automatiquement toutes les routes CRUD
*/

//Route::resource('cities', CityController::class);

/*
|--------------------------------------------------------------------------
| Exemple de Route Group avec prefix
|--------------------------------------------------------------------------
| Toutes les routes commencent par /admin
*/

//Route::prefix('admin')->group(function () {

    // URL : /admin/dashboard
    //Route::get('/dashboard', function () {
        //return 'Admin Dashboard';
    //});

    // URL : /admin/users
    //Route::get('/users', function () {
       // return 'Liste des utilisateurs';
    //});
//});


