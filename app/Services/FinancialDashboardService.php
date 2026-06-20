<?php

namespace App\Services;

use App\Models\ClientBalanceLog;
use App\Models\Currency;
use App\Models\Invoice;
use App\Models\Payment;
//ydv lsjo];ydv lsjo] غير مستخدم
class FinancialDashboardService
{
    /*
    |--------------------------------------------------------------------------
    | التحصيلات الفعلية
    | يستثني المسترجعات النقدية
    |--------------------------------------------------------------------------
    */
    public static function collections(int $branchId)
    {
        return Payment::query()

            ->where('branch_id', $branchId)

            ->where(function ($q) {
                $q->whereNull('payment_method')
                    ->orWhere('payment_method', '!=', 'refund');
            })

            ->selectRaw('
                currency_id,
                SUM(amount) as total
            ')

            ->groupBy('currency_id')

            ->with('currency')

            ->get();
    }

    /*
    |--------------------------------------------------------------------------
    | المسترجعات النقدية
    |--------------------------------------------------------------------------
    */
    public static function cashRefunds(int $branchId)
    {
        return Payment::query()

            ->where('branch_id', $branchId)

            ->where('payment_method', 'refund')

            ->selectRaw('
                currency_id,
                SUM(amount) as total
            ')

            ->groupBy('currency_id')

            ->with('currency')

            ->get();
    }

    /*
    |--------------------------------------------------------------------------
    | المسترجعات المضافة لرصيد العميل
    |--------------------------------------------------------------------------
    */
    public static function balanceRefunds(int $branchId)
    {
        return ClientBalanceLog::query()

            ->where('type', 'refund')

            ->selectRaw('
                currency_id,
                SUM(amount) as total
            ')

            ->groupBy('currency_id')

            ->with('currency')

            ->get();
    }

    /*
    |--------------------------------------------------------------------------
    | الذمم على العملاء
    |--------------------------------------------------------------------------
    */
    public static function clientReceivables(int $branchId)
    {
        return Invoice::query()

            ->where('branch_id', $branchId)

            ->where('is_refund', false)

            ->whereNotIn('status', [
                'cancelled',
                'rejected'
            ])

            ->where('remaining_amount', '>', 0)

            ->selectRaw('
                currency_id,
                SUM(remaining_amount) as total
            ')

            ->groupBy('currency_id')

            ->with('currency')

            ->get();
    }

    /*
    |--------------------------------------------------------------------------
    | الأرباح المؤكدة
    |--------------------------------------------------------------------------
    */
    public static function confirmedProfits(int $branchId): array
    {
        $result = [];

        $currencies = Currency::all();

        foreach ($currencies as $currency) {

            $profit = 0;

            $invoices = Invoice::query()

                ->where('branch_id', $branchId)

                ->where('currency_id', $currency->id)

                ->where('is_refund', false)

                ->whereNotIn('status', [
                    'cancelled',
                    'rejected'
                ])

                ->get();

            foreach ($invoices as $invoice) {

                if (
                    $invoice->total_amount <= 0
                ) {
                    continue;
                }

                $ratio =
                    $invoice->paid_amount
                    /
                    $invoice->total_amount;

                $recoveredCost =
                    $invoice->cost
                    *
                    $ratio;

                $profit +=
                    (
                        $invoice->paid_amount
                        -
                        $recoveredCost
                    );
            }

            $result[] = [

                'currency' => $currency,

                'profit' => round(
                    $profit,
                    2
                )
            ];
        }

        return collect($result)

            ->filter(
                fn($row) =>
                    $row['profit'] != 0
            )

            ->values()

            ->toArray();
    }

    /*
    |--------------------------------------------------------------------------
    | صافي التحصيل
    |--------------------------------------------------------------------------
    */
    public static function netCollections(int $branchId)
    {
        $currencies = Currency::all();

        $result = [];

        foreach ($currencies as $currency) {

            $collections =
                Payment::query()

                    ->where('branch_id', $branchId)

                    ->where('currency_id', $currency->id)

                    ->where(function ($q) {
                        $q->whereNull('payment_method')
                            ->orWhere('payment_method', '!=', 'refund');
                    })

                    ->sum('amount');

            $refundsCash =
                Payment::query()

                    ->where('branch_id', $branchId)

                    ->where('currency_id', $currency->id)

                    ->where('payment_method', 'refund')

                    ->sum('amount');

            $result[] = [

                'currency' => $currency,

                'collections' => $collections,

                'refunds' => $refundsCash,

                'net' => $collections - $refundsCash
            ];
        }

        return collect($result)

            ->filter(function ($row) {
                return
                    $row['collections'] != 0
                    ||
                    $row['refunds'] != 0;
            })

            ->values();
    }


}
