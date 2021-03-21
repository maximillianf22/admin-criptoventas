<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['middleware' => 'auth:api'], function () {
});
//payU post
Route::get('payU/{id}', 'ApiControllers\PayUController@show');
Route::get('payUResponse', 'ApiControllers\PayUController@responsePayU');

Route::post('/payConfirm', 'Administrator\OrdersController@payUConfirm');

Route::post('client/sendCode', 'ApiControllers\CustomersApiController@sendCode');
Route::post('client/confirmCode', 'ApiControllers\CustomersApiController@confirmCode');
Route::post('client/loggin', 'ApiControllers\ClientController@makeLogin');
Route::post('client/signup', 'ApiControllers\CustomersApiController@postRegister');
Route::post('client/updateProfile', 'ApiControllers\CustomersApiController@postEditProfile');
Route::post('client/updatePassword', 'ApiControllers\CustomersApiController@updatePassword');

Route::post('commerce/postCommerce', 'ApiControllers\CommercesApiController@postCommerce');
Route::get('commerce/getCommercesByType', 'ApiControllers\CommercesApiController@getCommercesByType');
Route::get('commerce/getCommercesByCategory', 'ApiControllers\GlobalCategoriesApiController@getCommercesByCategory');
Route::get('commerce/getCommercesCategories', 'ApiControllers\GlobalCategoriesApiController@getCommercesCategories');
Route::get('commerce/getCommerceDetails', 'ApiControllers\CommercesApiController@getCommerceDetails');
Route::get('commerce/getCommerceProductsCategories', 'ApiControllers\CategoriesApiController@getCommerceProductsCategories');
Route::get('commerce/getShippingHour', 'ApiControllers\ShippingApiController@getShippingHour');

//new api
Route::get('commerce/toList', 'ApiControllers\GlobalCategoriesApiController@commerceToList');
Route::get('product/getProductCommerceList', 'ApiControllers\ProductApiController@getProductCommerce');
Route::post('notification/sendApp', 'Administrator\ProfileController@notificationApp');

Route::post('update/state/order', 'Administrator\OrdersController@updateStateDomi');
//

Route::get('product/getProductsListCommerce', 'ApiControllers\ProductApiController@getProductsListCommerce');
Route::get('product/getProductDetails', 'ApiControllers\ProductApiController@getProductDetails');
Route::get('product/getProductsCategoriesList', 'ApiControllers\ProductApiController@getProductsListByCategoryByCommerce');
Route::get('product/getProductIngredients', 'ApiControllers\IngredientsApiControllers@getProductIngredients');

Route::post('coupons/getCoupon', 'ApiControllers\CouponsController@getCoupon');

Route::post('order/postOrder', 'ApiControllers\OrderApiController@postOrder');
Route::get('order/getListOrder', 'ApiControllers\OrderApiController@getListOrder');
Route::get('order/getOrderDetail', 'ApiControllers\OrderApiController@getOrderDetail');

//buscadores

Route::get('seachProducts', 'ApiControllers\ProductApiController@getProductsByseacher');
Route::get('searchCommerce', 'ApiControllers\CommercesApiController@searchCommerce');

/* Route::get('seachProductsBycommerce/{keyWord}/{commerce}','Administrator\ProductController@BuscadorByCommercio'); */
//oferts Products
Route::get('oferts', 'ApiControllers\CommercesApiController@ofertsPorducts');
Route::get('outstanding', 'ApiControllers\CommercesApiController@outstandingProducts');
Route::get('client/getListAddress', 'ApiControllers\AddressApiController@getListAddress');
Route::get('client/getAddressDetails', 'ApiControllers\AddressApiController@getAddressDetails');
Route::post('client/postDeleteAddress', 'ApiControllers\AddressApiController@postDeleteAddress');
Route::post('client/postUpdateAddress', 'ApiControllers\AddressApiController@postUpdateAddress');
Route::post('client/postAddAddress', 'ApiControllers\AddressApiController@postAddAddress');


Route::get('propina/getListTips', 'ApiControllers\TipsApiController@getListTips');

Route::post('distributor/postDistributor', 'ApiControllers\DistributorApiController@postDistributor');
Route::post('distributor/getDistributor', 'ApiControllers\DistributorApiController@getDistributor');
Route::get('distributor/comissions/byOrderid/{id}', 'ApiControllers\DistributorApiController@getDistributorByOrder');

Route::get('sliders/byCommerce', 'ApiControllers\SliderApiController@getList');

Route::get('home/sliders', 'ApiControllers\SliderApiController@getListG');

Route::get('parameters/getParametersValues', 'ApiControllers\ParameterApiController@getParametersValues');

Route::get('config/getMinShopping', 'ApiControllers\MinShoppingValueApiController@getMins');
