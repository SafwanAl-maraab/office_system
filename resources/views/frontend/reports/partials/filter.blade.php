<div class="bg-white dark:bg-gray-900
            rounded-2xl
            border border-gray-100 dark:border-gray-800
            p-5 shadow-sm">

    <form method="GET">

        <div class="grid
                    md:grid-cols-3
                    gap-4">

            <div>

                <label class="block mb-2 text-sm">

                    من تاريخ

                </label>

                <input
                    type="date"
                    name="date_from"
                    value="{{ $from }}"
                    class="w-full rounded-xl border">

            </div>

            <div>

                <label class="block mb-2 text-sm">

                    إلى تاريخ

                </label>

                <input
                    type="date"
                    name="date_to"
                    value="{{ $to }}"
                    class="w-full rounded-xl border">

            </div>

            <div class="flex items-end">

                <button
                    class="w-full
                           bg-indigo-600
                           hover:bg-indigo-700
                           text-white
                           py-3
                           rounded-xl">

                    تحديث التقرير

                </button>

            </div>

        </div>

    </form>

</div>
