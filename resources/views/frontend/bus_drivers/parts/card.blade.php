<div class="bg-white dark:bg-gray-800 shadow-lg rounded-xl p-5 space-y-4">

    <div class="flex justify-between items-center">

        <h3 class="font-bold text-lg">

            🚌 {{ $bus->plate_number }}

        </h3>

        <span class="text-xs px-2 py-1 rounded
@if($bus->status=='active') bg-green-100 text-green-600
@elseif($bus->status=='maintenance') bg-yellow-100 text-yellow-600
@else bg-red-100 text-red-600
@endif
">

{{ $bus->status }}

</span>

    </div>


    <div class="text-sm text-gray-500">

        موديل : {{ $bus->model ?? '-' }}

    </div>


    <div class="text-sm">

        المقاعد :

        <b>{{ $bus->capacity }}</b>

    </div>


    <div class="text-sm">

        الوكيل :

        <b>{{ $bus->agent->name ?? '-' }}</b>

    </div>


    {{-- drivers --}}

    <div>

        <p class="text-sm text-gray-500 mb-1">
            السائقين
        </p>

        <div class="flex flex-wrap gap-1">

            @foreach($bus->drivers as $driver)

                <span class="bg-gray-100 text-xs px-2 py-1 rounded">

{{ $driver->name }}

</span>

            @endforeach

        </div>

    </div>


    {{-- trip --}}

    @if($bus->currentTrip)

        <div class="bg-blue-50 p-2 rounded text-sm">

            🚍 رحلة حالية

            <br>

            {{ $bus->currentTrip->from_city }}
            →
            {{ $bus->currentTrip->to_city }}

        </div>

    @endif


    {{-- buttons --}}

    <div class="flex justify-between pt-3">

        <button
            class="text-blue-600 text-sm editBusBtn"
            data-bus='@json($bus)'>

            تعديل

        </button>

    </div>

</div>
