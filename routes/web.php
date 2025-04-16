<?php
  
use Illuminate\Support\Facades\Route;
  
use App\Http\Controllers\HomeController;
use App\Http\Controllers\FrontendController;
use App\Http\Controllers\Agent\ClientController;
  
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

// cache clear
Route::get('/clear', function() {
    Auth::logout();
    session()->flush();
    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('config:cache');
    Artisan::call('view:clear');
    return "Cleared!";
 });
//  cache clear
  
// Route::get('/', function () {
//     return view('welcome');
// });
  
Auth::routes();
Route::get('/', [FrontendController::class, 'index'])->name('homepage');
Route::get('/home', [HomeController::class, 'index'])->name('homepage');
  
/*------------------------------------------
--------------------------------------------
All Normal Users Routes List
--------------------------------------------
--------------------------------------------*/
Route::group(['prefix' =>'user/', 'middleware' => ['auth', 'is_user']], function(){
  
    Route::get('/home', [HomeController::class, 'index'])->name('home');
});
  

/*------------------------------------------
--------------------------------------------
All Agent Routes List
--------------------------------------------
--------------------------------------------*/
Route::group(['prefix' =>'manager/', 'middleware' => ['auth', 'is_manager']], function(){
  
    Route::get('/dashboard', [HomeController::class, 'managerHome'])->name('manager.dashboard');


    // client
    Route::get('/add-new-client', [ClientController::class, 'addClient'])->name('manager.addclient');
    Route::get('/client', [ClientController::class, 'getClient'])->name('manager.client');
    Route::post('/client', [ClientController::class, 'store']);
    Route::get('/new-clients', [ClientController::class, 'newClient'])->name('manager.newClient');
    Route::get('/processing-clients', [ClientController::class, 'processing'])->name('manager.processingclient');
    Route::get('/decline-clients', [ClientController::class, 'decline'])->name('manager.declineclient');
    Route::get('/completed-clients', [ClientController::class, 'completed'])->name('manager.completedclient');

    
    Route::get('/client-details/{id}', [ClientController::class, 'getClientInfo'])->name('admin.clientDetails');

    // download
    Route::get('/client-image-download/{id}', [ClientController::class, 'client_image_download'])->name('client_image.download');
    Route::get('/visa-image-download/{id}', [ClientController::class, 'visa_image_download'])->name('visa_image.download');
    Route::get('/manpower-image-download/{id}', [ClientController::class, 'manpower_image_download'])->name('manpower_image.download');
    Route::get('/passport-image-download/{id}', [ClientController::class, 'passport_image_download'])->name('passport_image.download');


    Route::post('/client-mofa-request', [ClientController::class,'createMofaRequest']);
    Route::post('/get-mofa-request', [ClientController::class,'getMofaRequest']);




});
 