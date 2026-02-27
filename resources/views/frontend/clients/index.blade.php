@extends('frontend.layouts.app')

@section('content')

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">

        <!-- HEADER -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">

            <div>
                <h2 class="text-2xl font-bold">إدارة العملاء</h2>
                <p class="text-gray-500 text-sm mt-1">
                    عرض وإدارة جميع عملاء الفرع
                </p>
            </div>

            <button type="button"
                    data-open-client
                    class="w-full sm:w-auto px-6 py-3 rounded-2xl bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-semibold shadow hover:scale-[1.02] transition">
                + إضافة عميل
            </button>

        </div>

        <!-- SEARCH -->
        <form method="GET" class="w-full sm:max-w-md">
            <input type="text"
                   name="search"
                   value="{{ request('search') }}"
                   placeholder="بحث بالاسم أو الجواز أو الهوية..."
                   class="w-full px-5 py-3 rounded-2xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 focus:ring-4 focus:ring-blue-200 outline-none">
        </form>

        <!-- CLIENT GRID -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

            @forelse($clients as $client)

                <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-3xl p-6 shadow hover:shadow-xl transition flex flex-col justify-between">

                    <div class="space-y-2">

                        <div class="flex justify-between items-start">

                            <h3 class="font-semibold text-lg">
                                {{ $client->full_name }}
                            </h3>

                            <span class="text-xs px-3 py-1 rounded-full
                        {{ $client->status ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600' }}">
                        {{ $client->status ? 'نشط' : 'موقوف' }}
                    </span>

                        </div>

                        <p class="text-sm text-gray-500">
                            📱 {{ $client->phone }}
                        </p>

                        @if($client->passport_number)
                            <p class="text-sm text-gray-500">
                                🛂 جواز: {{ $client->passport_number }}
                            </p>
                        @endif

                        @if($client->national_id)
                            <p class="text-sm text-gray-500">
                                🪪 هوية: {{ $client->national_id }}
                            </p>
                        @endif

                        @if($client->address)
                            <p class="text-sm text-gray-400">
                                📍 {{ $client->address }}
                            </p>
                        @endif

                    </div>

                    <!-- ACTIONS -->
                    <div class="flex justify-between pt-6">

                        <button type="button"
                                data-edit-client
                                data-client='@json($client)'
                                class="px-4 py-2 text-sm rounded-xl bg-yellow-100 text-yellow-600 hover:scale-105 transition">
                            تعديل
                        </button>

                        <button type="button"
                                data-delete-client
                                data-id="{{ $client->id }}"
                                class="px-4 py-2 text-sm rounded-xl bg-red-100 text-red-600 hover:scale-105 transition">
                            حذف
                        </button>

                    </div>

                </div>

            @empty

                <div class="col-span-full text-center py-16 text-gray-400">
                    لا يوجد عملاء حالياً
                </div>

            @endforelse

        </div>

        <!-- PAGINATION -->
        <div>
            {{ $clients->links() }}
        </div>

    </div>

    @include('frontend.clients.partials.modal')
    @include('frontend.clients.partials.delete')

@endsection
