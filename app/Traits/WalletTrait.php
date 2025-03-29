<?php

namespace App\Traits;

use App\Models\Common\NumberGenerator;
use App\Models\Payment\Wallet;

trait WalletTrait
{
    private function add($amount, $description, $transactionableEntity)
    {
        $wallet = $this->wallet();
        $wallet->balance = $wallet->balance + $amount;
        $wallet->save();

        $transactionableEntity->walletTransactions()->attach($wallet->id,[
            'number' => NumberGenerator::gen('App\Models\Payment\Wallet'),
            'description' => $description,
            'amount' => $amount,
            'credit' => $amount,
            'balance' => $wallet->balance,
            'currency_id' => currency()->id,
        ]);

        return $wallet;
    }

    private function deduct($amount, $description, $transactionableEntity)
    {
        $wallet = $this->wallet();
        $wallet->balance = $wallet->balance - $amount;
        $wallet->save();

        $transactionableEntity->walletTransactions()->attach($wallet->id,[
            'number' => NumberGenerator::gen(Wallet::class),
            'description' => $description,
            'amount' => -$amount,
            'debit' => -$amount,
            'balance' => $wallet->balance,
        ]);

        return $wallet;
    }

    public function wallet()
    {
        return Wallet::firstOrCreate(['walletable_id' => $this->model->id, 'walletable_type' => get_class($this->model)],[
            'walletable_id' => $this->model->id,
            'currency_id' => settings('default_currency', 1),
            'walletable_type' => get_class($this->model),
            'balance' => 0
        ]);
    }
}
