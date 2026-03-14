@extends('frontend.layouts.app')

@section('content')

    <div class="max-w-7xl mx-auto p-6 space-y-6">

        {{-- header --}}

        <div class="flex justify-between items-center">

            <h1 class="text-2xl font-bold">
                إدارة الباصات
            </h1>

            <button
                data-open-bus
                class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 rounded-xl">

                + إضافة باص

            </button>

        </div>


        {{-- grid buses --}}

        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">

            @foreach($buses as $bus)

                @include('frontend.bus_drivers.parts.card')

            @endforeach

        </div>


        <div class="pt-6">

            {{ $buses->links() }}

        </div>

    </div>


    @include('frontend.bus_drivers.parts.create')
    @include('frontend.bus_drivers.parts.edit')

@endsection

<script>

    const createModal = document.getElementById('createBusModal')

    document.querySelector('[data-open-bus]').onclick = () => {

        createModal.classList.remove('hidden')
        createModal.classList.add('flex')

    }

    function closeCreateBusModal(){

        createModal.classList.add('hidden')

    }


    const editModal = document.getElementById('editBusModal')

    document.querySelectorAll('.editBusBtn').forEach(btn => {

        btn.onclick = () => {

            const bus = JSON.parse(btn.dataset.bus)

            document.getElementById('edit_plate_number').value = bus.plate_number
            document.getElementById('edit_model').value = bus.model
            document.getElementById('edit_capacity').value = bus.capacity

            document.getElementById('editBusForm').action =
                `/dashboard/buses/${bus.id}`

            editModal.classList.remove('hidden')
            editModal.classList.add('flex')

        }

    })


    function closeEditBusModal(){

        editModal.classList.add('hidden')

    }

</script>
