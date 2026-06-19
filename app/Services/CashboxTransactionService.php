<?php

namespace App\Services;

use App\Models\CashboxTransaction;

class CashboxTransactionService
{
    public static function log(
        int $branchId,
        int $currencyId,
        float $amount,
        string $type,
        ?string $referenceType = null,
        ?int $referenceId = null,
        ?string $notes = null,
        ?int $employeeId = null
    ): CashboxTransaction {

        return CashboxTransaction::create([

            'branch_id'      => $branchId,

            'currency_id'    => $currencyId,

            'amount'         => $amount,

            'type'           => $type,

            'reference_type' => $referenceType,

            'reference_id'   => $referenceId,

            'notes'          => $notes,

            'created_by'     => $employeeId

        ]);
    }



}
