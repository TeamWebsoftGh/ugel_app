<?php

use App\Http\Controllers\Api\Mobile\ClientController;
use App\Http\Controllers\Api\Mobile\ClientInvestmentController;
use App\Http\Controllers\Api\Mobile\ClientLoanController;
use App\Http\Controllers\Api\Mobile\ConstituencyController;
use App\Http\Controllers\Api\Mobile\ElectionResultController;
use App\Http\Controllers\Api\Mobile\ElectoralAreaController;
use App\Http\Controllers\Api\Mobile\ExistingLoanController;
use App\Http\Controllers\Api\Mobile\LoanCollateralController;
use App\Http\Controllers\Api\Mobile\LoanGuarantorController;
use App\Http\Controllers\Api\Mobile\LoanRequirementController;
use App\Http\Controllers\Api\Mobile\ParliamentaryCandidateController;
use App\Http\Controllers\Api\Mobile\PaymentController;
use App\Http\Controllers\Api\Mobile\PollingStationController;
use App\Http\Controllers\Api\Mobile\PresidentialCandidateController;
use App\Http\Controllers\Api\Mobile\VoterController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Mobile App Routes
|--------------------------------------------------------------------------
*/


Route::group(['namespace' => 'Mobile', 'prefix' => 'ec'], function () {
    Route::get('election-results/create', [ElectionResultController::class, 'create']);
    Route::get('election-results/total-votes', [ElectionResultController::class, 'totalVotes']);
    Route::get('election-results/total-votes-by-region', [ElectionResultController::class, 'totalVotesByRegion']);
    Route::get('election-results/total-votes-by-constituency', [ElectionResultController::class, 'totalVotesByConstituency']);
    Route::get('election-results/total-votes-by-electoral-area', [ElectionResultController::class, 'totalVotesByElectoralArea']);
    Route::get('election-results/total-votes-by-polling-station', [ElectionResultController::class, 'totalVotesByPollingStation']);
    Route::get('election-results/polling-stations-vote-summary', [ElectionResultController::class, 'pollingStationVoteSummary']);
    Route::apiResource('election-results', ElectionResultController::class);

    Route::get('parliamentary-candidates/create', [ParliamentaryCandidateController::class, 'create']);
    Route::apiResource('parliamentary-candidates', ParliamentaryCandidateController::class);

    Route::get('presidential-candidates/create', [PresidentialCandidateController::class, 'create']);
    Route::apiResource('presidential-candidates', PresidentialCandidateController::class);
});
