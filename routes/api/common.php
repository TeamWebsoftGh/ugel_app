<?php

use App\Http\Controllers\Api\Mobile\ClientController;
use App\Http\Controllers\Api\Mobile\ClientInvestmentController;
use App\Http\Controllers\Api\Mobile\ClientLoanController;
use App\Http\Controllers\Api\Mobile\CommonController;
use App\Http\Controllers\Api\Mobile\ConstituencyController;
use App\Http\Controllers\Api\Mobile\ElectoralAreaController;
use App\Http\Controllers\Api\Mobile\ExistingLoanController;
use App\Http\Controllers\Api\Mobile\LoanCollateralController;
use App\Http\Controllers\Api\Mobile\LoanGuarantorController;
use App\Http\Controllers\Api\Mobile\LoanRequirementController;
use App\Http\Controllers\Api\Mobile\PaymentController;
use App\Http\Controllers\Api\Mobile\PollingStationController;
use App\Http\Controllers\Api\Mobile\RegionController;
use App\Http\Controllers\Api\Mobile\VoterController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Mobile App Routes
|--------------------------------------------------------------------------
*/


Route::group(['namespace' => 'Mobile', 'prefix' => 'common'], function () {
    Route::get('property-types/{id}', [CommonController::class, 'propertyTypeDetails']);
    Route::get('property-types', [CommonController::class, 'propertyTypes']);


});
