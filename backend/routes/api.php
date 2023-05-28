<?php

use App\Http\Controllers\API\ClientController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;

use App\Http\Controllers\AuthController;

use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\MarcaController;
use App\Http\Controllers\MotorcyclePartController;
use App\Http\Controllers\ProviderController;
use App\Models\MotorcyclePart;
use App\Http\Controllers\InvoiceDetailController;
use App\Http\Controllers\InvoiceController;



/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});



Route::post('register', [AuthController::class, 'register']);

Route::post('login', [AuthController::class, 'login']);





Route::middleware('auth:sanctum')->group(function () {

    Route::get('logout', [AuthController::class, 'logout']);
    Route::get('products', [ProductController::class, 'index']);
    Route::resource('categories', CategoriesController::class)->only(['index', 'store', 'show', 'update', 'destroy']);
    Route::resource('provider', ProviderController::class)->only(['index', 'store', 'show', 'update', 'destroy']); 
    Route::resource('client', ClientController::class)->only(['index', 'store', 'show', 'update', 'destroy']);
    Route::resource('marca', MarcaController::class)->only(['index', 'store', 'show', 'update', 'destroy']);

    
    
});


Route::resource('motorcyclepart', MotorcyclePartController::class)->only(['index', 'store', 'show', 'update', 'destroy']);

Route::resource('invoices', InvoiceController::class);
Route::resource('invoices.invoiceDetails', InvoiceDetailController::class)->shallow();


/* Route::put('motorcyclepart/{motorcyclepart}', [MotorcyclePartController::class, 'update']);



 Route::get('motorcyclepart', [MotorcyclePartController::class, 'index']);


Route::post('motorcyclepart', [MotorcyclePartController::class, 'store']);  */



//  Route::put('/categories/{category}', [CategoriesController::class, 'update']);









