<?php
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
	return Inertia\Inertia::render('Dashboard');
})->name('dashboard');

Route::group(['middleware' => ['auth']], function () {
    //
	Route::get('/', 'VenteController@index');
	Route::get('product/create', 'ProductController@create')->name('product.create');
	Route::resource('obr_declarations', ObrDeclarationController::class);
	Route::get('obr_declarations_hostory', [\App\Http\Controllers\ObrDeclarationController::class, 'hostory'])->name('obr_declarations_hostory');
	Route::get('sendInvoinceToObr/{invoince_id?}','ObrDeclarationController@sendInvoinceToObr' );

	Route::post('cancelInvoice','ObrDeclarationController@cancelInvoice' );
	Route::resource('stockes', StockeController::class);
	Route::resource('products', ProductController::class);
	Route::resource('clients', ClientController::class);
	Route::resource('categories', CategoryController::class);
	Route::resource('ventes', VenteController::class);
	Route::resource('orders', OrderController::class);
	Route::resource('entreprises', EntrepriseController::class);
	Route::resource('depenses', DepenseController::class);
	Route::resource('users', UserController::class);
	Route::resource('services', ServiceController::class);
	Route::resource('paimenent_dette' , PaiementDetteController::class);
	
	Route::get('update_price', 'CartController@update_product_price')->name('update_price');
	Route::get('update_emballage', 'CartController@update_emballage')->name('update_emballage');
	Route::get('update_quantite', 'CartController@update_quantite')->name('update_quantite');
	Route::get('rapport', 'StockeController@rapport')->name('rapport');

//Cart ROUTE
	Route::post('panier/ajouter','CartController@store')->name('panier.store');

	Route::get('panier/index','CartController@index')->name('panier.index');
	Route::get('panier/vente','CartController@vente')->name('panier.vente');

	Route::get('getClient/{id}', 'ClientController@getClient')->name('getClient');

	Route::delete('panier/{id}', 'CartController@destroy')->name('cart.destroy');
	Route::post('update_panier', 'CartController@updatePanier')->name('cart.update_panier');
	Route::get('journal', 'StockeController@journal')->name('stockes.journal');
	Route::get('canceledInvoince', 'StockeController@canceledInvoince')->name('stockes.journal');
	Route::delete('cancelFactures/{order_id}', 'StockeController@cancelFactures')->name('cancelFactures');
	Route::get('canceledInvoince', 'StockeController@canceledInvoince')->name('canceledInvoince');

	Route::get('journal_history', 'StockeController@journal_history')->name('journal_history');
	Route::get('fiche_stock', 'StockeController@fiche_stock')->name('fiche_stock');

	Route::get('/vider', function(){
		Cart::destroy();
	});
//Checkout Router PayMent
	Route::post('payement','CheckoutController@store')->name('payement');
	Route::post('add_quantite_stock','ProductController@add_quantite_stock')->name('add_quantite_stock');
	Route::get('add_view/{product}','ProductController@add_view')->name('add_view');
	Route::get('bon_entre', 'StockeController@bonEntre')->name('bon_entre');
	Route::get('paimenet_dette', 'CheckoutController@paimenetDette')->name('paimenet_dette');
});