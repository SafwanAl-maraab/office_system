<div class="bg-white dark:bg-gray-800 shadow rounded-xl p-5 space-y-4">

    <div class="flex justify-between items-center">

        <h3 class="font-bold text-lg text-gray-800 dark:text-white">
            🚌 {{ $record->bus->plate_number }}
        </h3>

        <span class="text-xs px-2 py-1 rounded

@if($record->bus->status=='active')
bg-green-100 text-green-600
@elseif($record->bus->status=='maintenance')
bg-yellow-100 text-yellow-600
@else
bg-red-100 text-red-600
@endif
">

{{ $record->bus->status }}

</span>

    </div>


    <div class="text-sm text-gray-500">
        الوكيل :
        <b>{{ $record->bus->agent->name ?? '-' }}</b>
    </div>


    <div class="text-sm">
        السائق :
        <b>{{ $record->driver->name }}</b>
    </div>


    <div class="text-sm">

        {{ substr($record->start_at,0,5) }}

        →

        {{ $record->end_at ? substr($record->end_at,0,5) : 'مفتوح' }}

    </div>


    @php

        $trip = App\Models\Trip::where('bus_id',$record->bus_id)
        ->whereIn('status',['scheduled','active'])
        ->first();

    @endphp


    @if($record->bus->currentTrip)

        @if($record->bus->currentTrip->status == 'scheduled')

            <div class="bg-yellow-50 dark:bg-yellow-900/30 border border-yellow-200 dark:border-yellow-800 p-2 rounded text-sm">

                🟡 رحلة مجدولة

                <div class="mt-1">

                    {{ $record->bus->currentTrip->from_city }}
                    →
                    {{ $record->bus->currentTrip->to_city }}

                </div>

            </div>

        @elseif($record->bus->currentTrip->status == 'in_progress')

            <div class="bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-800 p-2 rounded text-sm">

                🟢 رحلة جارية

                <div class="mt-1">

                    {{ $record->bus->currentTrip->from_city }}
                    →
                    {{ $record->bus->currentTrip->to_city }}

                </div>

            </div>

        @endif

    @else

        <div class="bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 p-2 rounded text-sm">

            ⚪ الحافلة متاحة حالياً

        </div>

    @endif

    <div class="flex justify-between pt-3 text-sm">

        <button
            class="text-blue-600 editBtn"
            data-id="{{ $record->id }}"
            data-start="{{ $record->start_at }}"
            data-end="{{ $record->end_at }}">

            تعديل

        </button>

        <button
            data-open-create
            data-bus="{{ $record->bus_id }}"
            class="text-blue-600 text-sm">

            ➕ إضافة سائق

        </button>

        <form method="POST"
              action="{{ route('dashboard.bus_assignments.destroy',$record->id) }}">

            @csrf
            @method('DELETE')

            <button class="text-red-600">
                حذف
            </button>

        </form>

    </div>

</div>
