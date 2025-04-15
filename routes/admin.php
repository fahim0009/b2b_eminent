<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ChartOfAccountController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AgentController;
use App\Http\Controllers\Admin\CodeMasterController;


/*------------------------------------------
--------------------------------------------
All Admin Routes List
--------------------------------------------
--------------------------------------------*/
Route::group(['prefix' =>'admin/', 'middleware' => ['auth', 'is_admin']], function(){
  
    Route::get('/dashboard', [HomeController::class, 'adminHome'])->name('admin.dashboard');
    //profile
    Route::get('/profile', [AdminController::class, 'profile'])->name('profile');
    Route::put('profile/{id}', [AdminController::class, 'adminProfileUpdate']);
    Route::post('changepassword', [AdminController::class, 'changeAdminPassword']);
    Route::put('image/{id}', [AdminController::class, 'adminImageUpload']);
    //profile end

    
    Route::get('/agent', [AgentController::class, 'index'])->name('admin.agent');
    Route::get('/agent-client/{id}', [AgentController::class, 'getClient'])->name('admin.agentClient');
    Route::get('/agent-tran/{id}', [AgentController::class, 'getTran'])->name('admin.agentTran');
    Route::post('/agent', [AgentController::class, 'store']);
    Route::get('/agent/{id}/edit', [AgentController::class, 'edit']);
    Route::post('/agent-update', [AgentController::class, 'update']);
    Route::get('/agent/{id}', [AgentController::class, 'delete']);
    
    
    Route::post('getchartofaccount', [ChartOfAccountController::class, 'getaccounthead']);
    Route::get('/chart-of-account', [ChartOfAccountController::class, 'index'])->name('admin.coa');
    Route::post('/chart-of-account', [ChartOfAccountController::class, 'store']);
    Route::get('/chart-of-account/{id}/edit', [ChartOfAccountController::class, 'edit']);
    Route::post('/chart-of-account-update', [ChartOfAccountController::class, 'update']);
    Route::get('/chart-of-account/{id}', [ChartOfAccountController::class, 'delete']);


    Route::get('/setting', [CodeMasterController::class, 'index'])->name('admin.setting');
    Route::post('/setting', [CodeMasterController::class, 'store']);
    Route::get('/setting/{id}/edit', [CodeMasterController::class, 'edit']);
    Route::post('/setting-update', [CodeMasterController::class, 'update']);
    Route::get('/setting/{id}', [CodeMasterController::class, 'delete']);

    

    

});
  