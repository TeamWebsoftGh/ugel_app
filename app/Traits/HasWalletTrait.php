<?php

namespace App\Traits;

use App\Models\Payment\Wallet;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

trait HasWalletTrait
{
//    private function wallet()
//    {
//        return $this->morphOne(Wallet::class, 'walletable')->withDefault();
//    }

    public function wallet()
    {
        return app()->makeWith('App\Services\WalletService', [
            'walletOwnerModel' => $this->getModel()
        ]);
    }

    public function transactions(): morphToMany
    {
        return $this->morphToMany(Wallet::class, 'walletable');
    }
}
