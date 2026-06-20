<div
    class="bg-white dark:bg-gray-900
           rounded-2xl
           border border-gray-100
           dark:border-gray-800
           shadow-sm
           p-6">

    <form
        method="GET"
        action="{{ route('reports.profit-analysis') }}">

        <div
            class="grid
                   grid-cols-1
                   md:grid-cols-3
                   gap-4">

            {{-- من تاريخ --}}

            <div>

                <label
                    class="block
                           text-sm
                           font-medium
                           text-gray-600
                           dark:text-gray-300
                           mb-2">

                    من تاريخ

                </label>

                <input
                    type="date"
                    name="date_from"
                    value="{{ $from }}"

                    class="w-full
                           px-4
                           py-3
                           rounded-xl
                           border
                           border-gray-300
                           dark:border-gray-700
                           bg-white
                           dark:bg-gray-800">

            </div>

            {{-- إلى تاريخ --}}

            <div>

                <label
                    class="block
                           text-sm
                           font-medium
                           text-gray-600
                           dark:text-gray-300
                           mb-2">

                    إلى تاريخ

                </label>

                <input
                    type="date"
                    name="date_to"
                    value="{{ $to }}"

                    class="w-full
                           px-4
                           py-3
                           rounded-xl
                           border
                           border-gray-300
                           dark:border-gray-700
                           bg-white
                           dark:bg-gray-800">

            </div>


            <div>

                <label
                    class="block mb-2 text-sm">

                    العملة

                </label>

                <select
                    name="currency_id"
                    class="w-full
               px-4 py-3
               rounded-xl
               border">

                    <option value="">

                        جميع العملات

                    </option>

                    @foreach(
                        $currencies as $currency
                    )

                        <option
                            value="{{ $currency->id }}"
                            @selected(
                                $currencyId ==
                                $currency->id
                            )>

                            {{ $currency->name }}
                            ({{ $currency->code }})

                        </option>

                    @endforeach

                </select>

            </div>

            {{-- زر البحث --}}



            <div
                class="flex
                       items-end">

                <button
                    type="submit"

                    class="w-full
                           px-4
                           py-3
                           rounded-xl
                           bg-blue-600
                           hover:bg-blue-700
                           text-white
                           font-semibold">

                    تحديث التقرير

                </button>

            </div>

        </div>

    </form>

</div>
