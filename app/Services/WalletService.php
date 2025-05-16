<?php

namespace App\Services;

use App\Models\Common\NumberGenerator;
use App\Models\Payment\Wallet;
use App\Traits\WalletTrait;

class WalletService
{
    use WalletTrait;

    private $model;
    private $wallet;

    /**
     * WalletService constructor.
     * @param $model
     */
    function __construct($model)
    {
        $this->model = $model;
    }

    public function balance()
    {
        return $this->wallet()->balance;
    }

    public function deposit($amount, $transactionableEntity)
    {
        return $this->add($amount, 'Deposit', $transactionableEntity);
    }

    public function gift($amount, $transactionableEntity, $description = NULL)
    {
        $description = ($description) ? $description : 'Gift';
        return $this->add($amount, 'Gift', $transactionableEntity);
    }

    public function pay($amount, $transactionableEntity)
    {
        return $this->deduct($amount, 'Spent', $transactionableEntity);
    }

    public function refund($amount, $transactionableEntity)
    {
        return $this->add($amount, 'Refund', $transactionableEntity);
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
            'balance' => $wallet->balance,
            'currency_id' => currency()->id,
        ]);

        return $wallet;
    }
}
