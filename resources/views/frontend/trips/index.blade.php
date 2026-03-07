@extends('frontend.layouts.app')

@section('content')

    <div class="p-6">

        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">

            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">
                الرحلات
            </h1>ذ

            <div class="flex gap-3">

                <form method="GET">

                    <input
                        type="text"
                        name="search"
                        value="{{ $search }}"
                        placeholder="بحث عن مدينة أو باص..."
                        class="px-4 py-2 border rounded-lg
dark:bg-gray-800 dark:border-gray-700 dark:text-white">

                </form>

                <button
                    onclick="openCreateTripModal()"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                    إضافة رحلة
                </button>

            </div>

        </div>



        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

            @foreach($trips as $trip)

                <div class="bg-white dark:bg-gray-800 shadow rounded-xl p-5">

                    <div class="flex justify-between items-center mb-3">

                        <div>

                            <h2 class="font-bold text-lg text-gray-800 dark:text-white">

                                {{ $trip->from_city }} → {{ $trip->to_city }}

                            </h2>

                            <p class="text-sm text-gray-500">

                                {{ $trip->bus->model }} - {{ $trip->bus->plate_number }}

                            </p>

                        </div>

                        <span class="text-xs px-2 py-1 rounded

@if($trip->status == 'scheduled') bg-blue-100 text-blue-700
@elseif($trip->status == 'in_progress') bg-yellow-100 text-yellow-700
@elseif($trip->status == 'completed') bg-green-100 text-green-700
@else bg-red-100 text-red-700
@endif

">

{{ $trip->status }}

</span>

                    </div>


                    <div class="space-y-1 text-sm text-gray-600 dark:text-gray-300">

                        <div>📅 {{ $trip->trip_date }}</div>

                        <div>⏰ {{ $trip->trip_time }}</div>

                        <div>
                            💰 شراء:
                            {{ $trip->purchase_price }}
                            {{ $trip->currency->symbol ?? '' }}
                        </div>

                        <div>
                            💵 بيع:
                            {{ $trip->sale_price }}
                            {{ $trip->currency->symbol ?? '' }}
                        </div>

                    </div>


                    @php

                        $booked = $trip->bookings->count();
                        $capacity = $trip->bus->capacity;

                    @endphp


                    <div class="mt-3 text-sm text-gray-700 dark:text-gray-300">

                        Seats
                        <strong>
                            {{ $booked }} / {{ $capacity }}
                        </strong>

                    </div>



                    <div class="flex justify-end gap-2 mt-4">

                        <button
                            onclick="openEditModal(
{{ $trip->id }},
'{{ $trip->bus_id }}',
'{{ $trip->from_city }}',
'{{ $trip->to_city }}',
'{{ $trip->trip_date }}',
'{{ $trip->trip_time }}',
'{{ $trip->purchase_price }}',
'{{ $trip->sale_price }}',
'{{ $trip->currency_id }}',
'{{ $trip->status }}',
`{{ $trip->notes }}`
)"
                            class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded">
                            تعديل
                        </button>

                        <button
                            onclick="openDeleteModal({{ $trip->id }})"
                            class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded">
                            حذف
                        </button>

                    </div>

                </div>

            @endforeach

        </div>



        <div class="mt-6">

            {{ $trips->links() }}

        </div>

    </div>



    @include('frontend.trips.parts.create')

    @include('frontend.trips.parts.edit')

    @include('frontend.trips.parts.delete')



    <script>


        function openCreateModal(){

            document.getElementById('createModal').classList.remove('hidden')

            document.getElementById('createModal').classList.add('flex')

        }


        function closeCreateModal(){

            document.getElementById('createModal').classList.remove('flex')

            document.getElementById('createModal').classList.add('hidden')

        }



        function openEditModal(
            id,
            bus_id,
            from_city,
            to_city,
            trip_date,
            trip_time,
            purchase_price,
            sale_price,
            currency_id,
            status,
            notes
        ){

            document.getElementById('editModal').classList.remove('hidden')

            document.getElementById('editModal').classList.add('flex')

            document.getElementById('editForm').action="/frontend/trips/"+id

            document.getElementById('edit_bus_id').value = bus_id
            document.getElementById('edit_from_city').value = from_city
            document.getElementById('edit_to_city').value = to_city
            document.getElementById('edit_trip_date').value = trip_date
            document.getElementById('edit_trip_time').value = trip_time
            document.getElementById('edit_purchase_price').value = purchase_price
            document.getElementById('edit_sale_price').value = sale_price
            document.getElementById('edit_currency_id').value = currency_id
            document.getElementById('edit_status').value = status
            document.getElementById('edit_notes').value = notes

        }


        function closeEditModal(){

            document.getElementById('editModal').classList.remove('flex')

            document.getElementById('editModal').classList.add('hidden')

        }



        function openDeleteModal(id){

            document.getElementById('deleteModal').classList.remove('hidden')

            document.getElementById('deleteModal').classList.add('flex')

            document.getElementById('deleteForm').action="/frontend/trips/"+id

        }


        function closeDeleteModal(){

            document.getElementById('deleteModal').classList.remove('flex')

            document.getElementById('deleteModal').classList.add('hidden')

        }


    </script>


@endsection
