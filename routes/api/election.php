<?php

use App\Http\Controllers\Api\Mobile\ClientController;
use App\Http\Controllers\Api\Mobile\ClientInvestmentController;
use App\Http\Controllers\Api\Mobile\ClientLoanController;
use App\Http\Controllers\Api\Mobile\PropertyController;
use App\Http\Controllers\Api\Mobile\MaintenanceController;
use App\Http\Controllers\Api\Mobile\ElectoralAreaController;
use App\Http\Controllers\Api\Mobile\ExistingLoanController;
use App\Http\Controllers\Api\Mobile\LoanCollateralController;
use App\Http\Controllers\Api\Mobile\LoanGuarantorController;
use App\Http\Controllers\Api\Mobile\LoanRequirementController;
use App\Http\Controllers\Api\Mobile\ParliamentaryCandidateController;
use App\Http\Controllers\Api\Mobile\PaymentController;
use App\Http\Controllers\Api\Mobile\PollingStationController;
use App\Http\Controllers\Api\Mobile\BookingController;
use App\Http\Controllers\Api\Mobile\VoterController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Mobile App Routes
|--------------------------------------------------------------------------
*/


Route::group(['namespace' => 'Mobile', 'prefix' => 'ec'], function () {
    Route::get('election-results/create', [MaintenanceController::class, 'create']);
    Route::get('election-results/total-votes', [MaintenanceController::class, 'totalVotes']);
    Route::get('election-results/total-votes-by-region', [MaintenanceController::class, 'totalVotesByRegion']);
    Route::get('election-results/total-votes-by-constituency', [MaintenanceController::class, 'totalVotesByConstituency']);
    Route::get('election-results/total-votes-by-electoral-area', [MaintenanceController::class, 'totalVotesByElectoralArea']);
    Route::get('election-results/total-votes-by-polling-station', [MaintenanceController::class, 'totalVotesByPollingStation']);
    Route::get('election-results/polling-stations-vote-summary', [MaintenanceController::class, 'pollingStationVoteSummary']);
    Route::apiResource('election-results', MaintenanceController::class);

    Route::get('parliamentary-candidates/create', [ParliamentaryCandidateController::class, 'create']);
    Route::apiResource('parliamentary-candidates', ParliamentaryCandidateController::class);

    Route::get('presidential-candidates/create', [BookingController::class, 'create']);
    Route::apiResource('presidential-candidates', BookingController::class);
});
