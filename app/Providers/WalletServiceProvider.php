<?php

namespace App\Providers;

use App\Models\Payment\Payment;
use App\Services\WalletService;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;

class WalletServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(WalletService::class, function ($app, $params) {
            return new WalletService($params['walletOwnerModel']);
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Relation::morphMap([
            'payment'           => Payment::class,
            'order'             => Order::class,
            // We cannot add App\User as it is being used in Spatie Permission
        ]);
    }
}
