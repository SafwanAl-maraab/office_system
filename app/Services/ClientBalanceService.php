<?php

namespace App\Services;

use App\Models\ClientBalanceLog;
use App\Models\Currency;

class ClientBalanceService
{
    /*
    |--------------------------------------------------------------------------
    | رصيد عميل بعملة معينة
    |--------------------------------------------------------------------------
    */

    public static function getBalance(
        int $clientId,
        int $currencyId
    ): float {

        return (float)
        ClientBalanceLog::where(
            'client_id',
            $clientId
        )
            ->where(
                'currency_id',
                $currencyId
            )
            ->sum('amount');
    }

    /*
    |--------------------------------------------------------------------------
    | جميع أرصدة العميل
    |--------------------------------------------------------------------------
    */

    public static function getBalances(
        int $clientId
    ): array {

        $result = [];

        $currencies = Currency::where(
            'status',
            true
        )->get();

        foreach($currencies as $currency){

            $balance =
                self::getBalance(
                    $clientId,
                    $currency->id
                );

            if($balance == 0){
                continue;
            }

            $result[] = [

                'currency_id' =>
                    $currency->id,

                'currency_code' =>
                    $currency->code,

                'currency_name' =>
                    $currency->name,

                'balance' =>
                    round($balance,2),

                'is_credit' =>
                    $balance > 0,

                'is_debit' =>
                    $balance < 0
            ];
        }

        return $result;
    }

    /*
    |--------------------------------------------------------------------------
    | هل لدى العميل رصيد
    |--------------------------------------------------------------------------
    */

    public static function hasBalance(
        int $clientId,
        int $currencyId,
        float $amount
    ): bool {

        return self::getBalance(
                $clientId,
                $currencyId
            ) >= $amount;
    }

    /*
    |--------------------------------------------------------------------------
    | إضافة حركة
    |--------------------------------------------------------------------------
    */

    public static function addLog(
        array $data
    ): void {

        ClientBalanceLog::create($data);
    }
}
