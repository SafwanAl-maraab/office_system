@extends('frontend.layouts.app')

@section('content')

<div class="p-6 space-y-8">

    <!-- HEADER -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

        <div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">
                إدارة التأشيرات
            </h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">
                عرض وتنظيم جميع عمليات التأشيرات
            </p>
        </div>

        <button onclick="openCreateModal()"
            class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl shadow transition">
            ➕ إضافة تأشيرة
        </button>

    </div>


    <!-- SEARCH + FILTER -->
    <div class="bg-white dark:bg-gray-900 rounded-2xl p-6 shadow border border-gray-200 dark:border-gray-700">

        <form method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">

            <!-- SEARCH -->
            <input type="text"
                   name="search"
                   value="{{ request('search') }}"
                   placeholder="🔎 البحث باسم العميل"
                   class="w-full border border-gray-300 dark:border-gray-700 
                          bg-white dark:bg-gray-800 
                          text-gray-800 dark:text-white 
                          rounded-xl px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none">

            <!-- FILTER -->
            <select name="visa_type"
                class="w-full border border-gray-300 dark:border-gray-700 
                       bg-white dark:bg-gray-800 
                       text-gray-800 dark:text-white 
                       rounded-xl px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none">

                <option value="">جميع الأنواع</option>

                @foreach($visaTypes as $type)
                    <option value="{{ $type->id }}"
                        {{ request('visa_type') == $type->id ? 'selected' : '' }}>
                        {{ $type->name }}
                    </option>
                @endforeach

            </select>

            <!-- SUBMIT -->
            <button type="submit"
                class="px-4 py-2 bg-gray-800 hover:bg-gray-900 text-white rounded-xl transition">
                تطبيق
            </button>

        </form>

    </div>


    <!-- CARDS -->
    @if($visas->count())

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">

        @foreach($visas as $visa)

        <div class="bg-white dark:bg-gray-900 rounded-2xl shadow border border-gray-200 dark:border-gray-700 hover:shadow-lg transition">

            <!-- CARD HEADER -->
            <div class="p-5 border-b border-gray-100 dark:border-gray-800 flex justify-between items-start">

                <div>
                    <h3 class="font-semibold text-gray-800 dark:text-white text-base">
                        {{ $visa->client->full_name ?? '-' }}
                    </h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        {{ $visa->passport_number }}
                    </p>
                </div>

                <!-- STATUS BADGE -->
                <span class="text-xs px-3 py-1 rounded-full
                    @if($visa->status == 'issued')
                        bg-green-100 text-green-700
                    @elseif($visa->status == 'cancelled')
                        bg-red-100 text-red-700
                    @else
                        bg-yellow-100 text-yellow-700
                    @endif">
                    {{ ucfirst($visa->status) }}
                </span>

            </div>


            <!-- CARD BODY -->
            <div class="p-5 space-y-3 text-sm text-gray-600 dark:text-gray-300">

                <div class="flex justify-between">
                    <span>نوع التأشيرة</span>
                    <span class="font-medium">
                        {{ $visa->visaType->name ?? '-' }}
                    </span>
                </div>

                <div class="flex justify-between">
                    <span>سعر البيع</span>
                    <span class="font-semibold text-blue-600 dark:text-blue-400">
                        {{ number_format($visa->sale_price,2) }}
                    </span>
                </div>

                <div class="flex justify-between">
                    <span>التكلفة</span>
                    <span>
                        {{ number_format($visa->cost_price,2) }}
                    </span>
                </div>

                @if($visa->agent)
                <div class="flex justify-between">
                    <span>الوكيل</span>
                    <span>{{ $visa->agent->name }}</span>
                </div>
                @endif

            </div>

<div class="flex flex-wrap gap-3">
            <!-- ACTIONS -->
            <div class="p-4 bg-gray-50 dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 flex flex-wrap gap-2 justify-between">

                <a href="{{ route('visas.show', $visa->id) }}"
                   class="px-3 py-1.5 text-sm bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 rounded-lg transition">
                    عرض
                </a>



              <button

    onclick="openStatusModal()"
    class="group relative inline-flex items-center gap-2 px-4 py-2
           bg-gradient-to-r from-blue-600 to-blue-700
           hover:from-blue-700 hover:to-blue-800
           text-white text-sm font-medium
           rounded-2xl shadow-md hover:shadow-lg
           transition-all duration-300">

    <svg xmlns="http://www.w3.org/2000/svg"
         class="w-4 h-4 opacity-90 group-hover:rotate-6 transition"
         fill="none"
         viewBox="0 0 24 24"
         stroke="currentColor">
        <path stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M9 5h6M9 9h6M9 13h6M9 17h6" />
    </svg>

    تغيير الحالة
</button>

<button
    onclick="openTripModal()"
    class="group relative inline-flex items-center gap-2 px-4 py-2
           bg-gradient-to-r from-emerald-600 to-emerald-700
           hover:from-emerald-700 hover:to-emerald-800
           text-white text-sm font-medium
           rounded-2xl shadow-md hover:shadow-lg
           transition-all duration-300">

    <svg xmlns="http://www.w3.org/2000/svg"
         class="w-4 h-4 opacity-90 group-hover:rotate-6 transition"
         fill="none"
         viewBox="0 0 24 24"
         stroke="currentColor">
        <path stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M3 7h18M3 12h18M3 17h18" />
    </svg>

    ربط بحملة
</button>

</div>

            </div>

        </div>

        @endforeach

    </div>


    <!-- PAGINATION -->
    <div class="mt-6">
        {{ $visas->withQueryString()->links() }}
    </div>

    @else

    <div class="bg-white dark:bg-gray-900 rounded-2xl p-10 text-center shadow border border-gray-200 dark:border-gray-700">
        <p class="text-gray-500 dark:text-gray-400">
            لا توجد تأشيرات حالياً
        </p>
    </div>

    @endif

</div>


<!-- STATUS MODAL -->
<div id="statusModal"
     class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50">

    <div class="bg-white dark:bg-gray-900 rounded-2xl p-6 w-full max-w-md shadow-xl">

        <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-4">
            تغيير حالة التأشيرة
        </h3>

        <form method="POST" id="statusForm">
            @csrf

            <select name="status"
                class="w-full border border-gray-300 dark:border-gray-700
                       bg-white dark:bg-gray-800
                       text-gray-800 dark:text-white
                       rounded-xl px-4 py-2 mb-4 focus:ring-2 focus:ring-blue-500 focus:outline-none">

                <option value="pending">Pending</option>
                <option value="issued">Issued</option>
                <option value="cancelled">Cancelled</option>

            </select>

            <div class="flex justify-end gap-3">
                <button type="button"
                        onclick="closeStatusModal()"
                        class="px-4 py-2 bg-gray-400 text-white rounded-lg">
                    إلغاء
                </button>

                <button type="submit"
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg">
                    حفظ
                </button>
            </div>

        </form>

    </div>
</div>


<!-- CREATE MODAL -->
@include('frontend.visas.partials.create-modal')


@include('frontend.visas.partials.trip_group')

<script>
function openStatusModal(id, currentStatus) {
    const form = document.getElementById('statusForm');
    form.action = `/dashboard/visas/${id}/change-status`;
    form.querySelector('select').value = currentStatus;
    document.getElementById('statusModal').classList.remove('hidden');
}

function closeStatusModal() {
    document.getElementById('statusModal').classList.add('hidden');
}

function openCreateModal(){
    document.getElementById('createModal').classList.remove('hidden');
}

function closeCreateModal(){
    document.getElementById('createModal').classList.add('hidden');
}

window.addEventListener('click', function(e){
    if(e.target.id === 'statusModal'){
        closeStatusModal();
    }
    if(e.target.id === 'createModal'){
        closeCreateModal();
    }


});

</script>



@endsection