<?php

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

/* Route::get('/fixVariationFotos', 'HomeController@fixFotos'); */

Route::post('countDashboard', 'Administrator\DashboardController@contadores');
Route::post('reporte', 'Administrator\DashboardController@reportes')->name('reportes');
Route::view('/unauthorized', 'errors.401')->name('error.401');
Route::get('/', 'Auth\LoginController@showLoginForm');

Route::get('/confirmCellphone', 'Auth\LoginController@showSendCodeForm')->name('auth.confirmCellphone');
Route::post('/confirmCellphone/validate', 'Auth\LoginController@confirmCode')->name('auth.confirmCellphone.validate');

Route::get('/confirmCode', 'Auth\LoginController@showConfirmCodeForm')->name('auth.confirmCode');
Route::post('/confirmCode/validate', 'Auth\LoginController@validateConfirmCode')->name('auth.confirmCode.validate');

Route::get('/changePassword', 'Auth\LoginController@showChangePasswordForm')->name('auth.changePassword');
Route::post('/changePassword/updatePassword', 'Auth\LoginController@updatePassword')->name('auth.changePassword.update');

Route::get('/generatePassword', 'UserController@funGeneratePassword');

Route::group(['prefix' => 'administrator', 'middleware' => 'auth'], function () {
    Route::post('canceleOrder',  'Administrator\OrdersController@canceleOrder')->name('cancelOrder');

    Route::view('/inicio', 'welcome')->name('administrator.home');

    Route::get('/dashboard', 'Administrator\DashboardController@index')->name('dashboard')->middleware('checkPermit:dashboard');

    Route::resource('/users', 'UserController')->middleware('checkPermit:users');

    Route::get('/addresses/show', 'Administrator\AddressController@funShow')->middleware('checkPermit:users');
    Route::delete('/addresses/delete', 'Administrator\AddressController@funDelete')->middleware('checkPermit:users');
    Route::resource('/addresses', 'Administrator\AddressController')->middleware('checkPermit:users');
    Route::resource('coupons', 'Administrator\CuponesController')->middleware('checkPermit:coupons');
    Route::get('/categories/showByCommerce', 'Administrator\CategoriesController@funGetCategoriesBycommerce')->middleware('checkPermit:categories');
    Route::get('/categories/showByCommerce2', 'Administrator\CategoriesController@funGetCategoriesBycommerce2')->middleware('checkPermit:categories');

    Route::resource('/categories', 'Administrator\CategoriesController')->middleware('checkPermit:categories');

    Route::get('/commerces/category/showByCommerceType', 'Administrator\commercesCategoryController@funGetCategoriesByCommerceType')->middleware('checkPermit:categories');
    Route::delete('/commerces/category/delete', 'Administrator\commercesCategoryController@funDelete')->middleware('checkPermit:commercesCategory');
    Route::get('/commerces/categories/show/{id}', 'Administrator\CommercesController@categoriesShow')->name('commerces.category')->middleware('checkPermit:commerces');
    Route::post('/commerces/categories/create', 'Administrator\CommercesController@categoriesStore')->name('commerces.category.store')->middleware('checkPermit:commerces');
    Route::get('/commerces/categories', 'Administrator\CommercesController@funDelete')->middleware('checkPermit:commerces');
    Route::delete('/commerces/categories/', 'Administrator\CommercesController@categoriesDelete')->name('commerces.category.delete')->middleware('checkPermit:commerces');
    Route::resource('/commerces/category', 'Administrator\commercesCategoryController')->middleware('checkPermit:commercesCategory');

    Route::delete('/commerces/delete', 'Administrator\CommercesController@funDelete')->middleware('checkPermit:commerces');
    Route::put('/commerce/activate', 'Administrator\CommercesController@activateCommerce')->name('commerce.activate')->middleware('checkPermit:commerces');
    //SLIDE CRUD FROM COMMERCE USER
    Route::get('/slider', 'Administrator\slidersController@slidersCommerce')->name('slider.index')->middleware('checkPermit:sliders');
    Route::get('/slider/{id}/edit', 'Administrator\slidersController@editSliderCommerce')->name('slider.edit')->middleware('checkPermit:sliders');
    Route::put('/slider/{id}/update', 'Administrator\slidersController@updateSliderCommerce')->name('slider.update')->middleware('checkPermit:sliders');
    //GLOBAL SLIDE CRUD
    Route::get('/gslider','Administrator\slidersController@slidersGlobal')->name('gslider.index')->middleware('checkPermit:sliders');
    Route::get('/gslider/{id}/edit', 'Administrator\slidersController@editSliderGlobal')->name('gslider.edit')->middleware('checkPermit:sliders');
    Route::post('/gslider/store', 'Administrator\slidersController@storeSliderGlobal')->name('gslider.store')->middleware('checkPermit:sliders');
    Route::put('/gslider/{id}/update', 'Administrator\slidersController@updateSliderGlobal')->name('gslider.update')->middleware('checkPermit:sliders');
    Route::delete('/gslider/delete', 'Administrator\slidersController@funDeleteG')->middleware('checkPermit:sliders');
    //
    Route::get('commerces/sliders/view/{id}', 'Administrator\slidersController@view')->name('sliders.view')->middleware('checkPermit:sliders');
    Route::delete('commerces/sliders/delete', 'Administrator\slidersController@funDelete')->middleware('checkPermit:sliders');
    Route::resource('commerces/sliders', 'Administrator\slidersController')->middleware('checkPermit:sliders');

    Route::resource('/commerces', 'Administrator\CommercesController')->middleware('checkPermit:commerces');

    Route::resource('/custommers', 'Administrator\CustommerController')->middleware('checkPermit:customers');
    Route::put('/customers/activateCode', 'Administrator\CustommerController@activateCodeConfirm')->name('customer.activate')->middleware('checkPermit:customers');
    Route::resource('/distributors', 'Administrator\DistributorController')->middleware('checkPermit:distributors');

    Route::put('/orders/updateState', 'Administrator\OrdersController@updateState')->middleware('checkPermit:orders');
    Route::resource('/orders', 'Administrator\OrdersController')->middleware('checkPermit:orders');

    Route::resource('/parameters', 'Administrator\ParametersController')->middleware('checkPermit:parameters');

    Route::resource('/parametersValues', 'Administrator\ParameterValuesController')->middleware('checkPermit:parameters');
    Route::get('/product/updatePrices/{id}', 'Administrator\ProductController@updatePrices');
    Route::get('/product/updatePricesR/{id}', 'Administrator\ProductController@updatePricesRestaurant');
    Route::post('/product/fungetupdatePrices', 'Administrator\ProductController@fungetupdatePrices');
    Route::post('/product/fungetupdatePricesR', 'Administrator\ProductController@fungetupdatePricesR');

    Route::get('/products/commerces', 'Administrator\ProductController@listCommerces')->name('products.commerce.index')->middleware('checkPermit:productsCommerce');
    Route::get('/products/commerce/{commerce}/products', 'Administrator\ProductController@listProductsByCommerce')->name('products.commerce.show')->middleware('checkPermit:productsCommerce');
    Route::resource('/products', 'Administrator\ProductController')->middleware('checkPermit:productsCommerce');

    Route::get('/product/commerce/{commerce}/market/create', 'Administrator\ProductMarketController@createMarketProduct')->name('commerce.market.create')->middleware('checkPermit:productsCommerce');
    Route::post('/product/market/variation/store', 'Administrator\ProductMarketController@storeVariation')->name('market.variation.store');
    Route::put('/product/market/variation/update/{id}', 'Administrator\ProductMarketController@updateVariation')->name('market.variation.update');
    Route::get('/product/market/variation/{id}', 'Administrator\ProductMarketController@showVariation')->name('market.variation.show');
    Route::delete('/product/market/variation/{id}', 'Administrator\ProductMarketController@deleteVariation')->name('market.variation.delete');
    Route::resource('/product/market', 'Administrator\ProductMarketController')->middleware('checkPermit:productsCommerce');

    Route::get('/product/commerce/{commerce}/restaurant/create', 'Administrator\ProductRestaurantController@createRestaurantProduct')->name('commerce.restaurant.create')->middleware('checkPermit:productsCommerce');
    Route::resource('/product/restaurant', 'Administrator\ProductRestaurantController')->middleware('checkPermit:productsCommerce');

    Route::get('/ingredients/byCategory/{category}', 'Administrator\IngredientsController@showIngredientsByCategory')->middleware('checkPermit:productsCommerce');;
    Route::resource('/ingredients', 'Administrator\IngredientsController')->middleware('checkPermit:productsCommerce');

    Route::resource('/ingredientsCategories', 'Administrator\IngredientsCategoryController');

    Route::resource('/units', 'UnitsController')->middleware('checkPermit:units');

    Route::get('/minimoCompras', ['uses' => 'Commerce\MinimumShoppingValueController@minCompra', 'as' => 'admin.minCompra']);
    Route::post('/minimoCompras/save', ['uses' => 'Commerce\MinimumShoppingValueController@saveMinCompra', 'as' => 'admin.minCompra.save']);
    Route::get('/minimCompra/byCommerce', 'Commerce\MinimumShoppingValueController@showByCommerce');
    Route::resource('/minShoppingValue', 'Commerce\MinimumShoppingValueController');

    Route::resource('/permits', 'Administrator\PermitsController')->middleware('checkPermit:permits');

    Route::resource('/rol', 'Administrator\RolController')->middleware('checkPermit:permits');

    Route::resource('/tips', 'TipsController')->middleware('checkPermit:tips');

    Route::get('/shipping/byCommerceByWeekday', 'Administrator\ShippingController@showByCommerce')->middleware('checkPermit:shipping');
    Route::resource('/shipping', 'Administrator\ShippingController')->middleware('checkPermit:shipping');
    Route::resource('/profile', 'Administrator\ProfileController');

    //Factura
    Route::get('factura/{id}', 'Administrator\OrdersController@factura')->name('factura')->middleware('checkPermit:orders');;
});


Auth::routes();
