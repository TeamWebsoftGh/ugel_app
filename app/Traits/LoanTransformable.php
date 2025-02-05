<?php

namespace App\Traits;

use App\Models\Client\Client;
use App\Models\Loan\Loan;
use App\Repositories\CurrencyRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

trait LoanTransformable
{
    /**
     * Transform the product
     *
     * @param Client $customer
     * @return array
     */
    protected function transformLoan(Loan $loan): array
    {
        $prod = [];
        $prod['loan_id'] = $loan->id;
        $prod['loan_product_name'] = $loan->loan_product->name;
        $prod['loan_product_id'] = $loan->loan_product->id;
        $prod['loan_term'] = $loan->loan_term;
        $prod['repayment_frequency_type'] = $loan->repayment_frequency_type;
        $prod['repayment_frequency'] = $loan->repayment_frequency;
        $prod['reference'] = $loan->reference;
        $prod['balance'] = $loan->balance;
        $prod['principal'] = $loan->principal;
        $prod['applied_amount'] = $loan->applied_amount;
        $prod['expected_disbursement_date'] = $loan->expected_disbursement_date;
        $prod['expected_first_payment_date'] =$loan->expected_first_payment_date;
        $prod['approved_amount'] = $loan->approved_amount;
        $prod['status'] = $loan->status;
        $prod['interest_rate'] = $loan->interest_rate;

        return $prod;
    }

    protected function transformFullLoan(Loan $loan): array
    {
        $prod = [];
        $prod['loan_id'] = $loan->id;
        $prod['loan_product_name'] = $loan->loan_product->name;
        $prod['loan_product_id'] = $loan->loan_product->id;
        $prod['loan_term'] = $loan->loan_term;
        $prod['repayment_frequency_type'] = $loan->repayment_frequency_type;
        $prod['repayment_frequency'] = $loan->repayment_frequency;
        $prod['reference'] = $loan->reference;
        $prod['balance'] = $loan->balance;
        $prod['principal'] = $loan->principal;
        $prod['applied_amount'] = $loan->applied_amount;
        $prod['expected_disbursement_date'] = $loan->expected_disbursement_date;
        $prod['first_payment_date'] =$loan->first_payment_date;
        $prod['approved_amount'] = $loan->approved_amount;
        $prod['status'] = $loan->status;
        $prod['interest_rate'] = $loan->interest_rate;

        $prod['loan_schedule'] = $loan->loan_repayment_schedules;
        $prod['existing_loans'] = $loan->existingLoans;
        $prod['transactions'] = $loan->transactions;
        $prod['personal_details'] = $loan->transactions;

        return $prod;
    }
}
