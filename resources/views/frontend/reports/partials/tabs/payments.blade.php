<div class="overflow-x-auto">

    <table class="w-full">

        <thead>

        <tr class="border-b">

            <th class="p-3 text-right">
                التاريخ
            </th>

            <th class="p-3 text-right">
                العميل
            </th>

            <th class="p-3 text-right">
                المبلغ
            </th>

        </tr>

        </thead>

        <tbody>

        @foreach($latestPayments as $payment)

            <tr class="border-b">

                <td class="p-3">
                    {{ $payment->created_at->format('Y-m-d H:i') }}
                </td>

                <td class="p-3">
                    {{ $payment->client->name ?? '-' }}
                </td>

                <td class="p-3 text-green-600 font-bold">
                    {{ number_format($payment->amount,2) }}
                </td>

            </tr>

        @endforeach

        </tbody>

    </table>

</div>
