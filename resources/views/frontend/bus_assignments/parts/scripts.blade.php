<script>

    const createModal = document.getElementById('createModal')

    document.querySelectorAll('[data-open-create]').forEach(btn=>{

        btn.onclick=()=>{

            document.getElementById('create_bus').value = btn.dataset.bus ?? ''

            createModal.classList.remove('hidden')
            createModal.classList.add('flex')

        }

    })

    function closeCreate(){

        createModal.classList.add('hidden')

    }



    const editModal = document.getElementById('editModal')

    document.querySelectorAll('.editBtn').forEach(btn=>{

        btn.onclick=()=>{

            document.getElementById('edit_start').value =
                btn.dataset.start.substring(0,5)

            document.getElementById('edit_end').value =
                btn.dataset.end ? btn.dataset.end.substring(0,5) : ''

            document.getElementById('editForm').action =
                `/dashboard/bus-assignments/${btn.dataset.id}`

            editModal.classList.remove('hidden')
            editModal.classList.add('flex')

        }

    })




</script>

<script>

    const editModal = document.getElementById('editModal')

    document.querySelectorAll('.editBtn').forEach(btn => {

        btn.addEventListener('click',function(){

            const id = this.dataset.id
            const start = this.dataset.start
            const end = this.dataset.end

            document.getElementById('edit_start').value = start.substring(0,5)

            document.getElementById('edit_end').value =
                end ? end.substring(0,5) : ''

            document.getElementById('editForm').action =
                "/dashboard/bus-assignments/"+id

            editModal.classList.remove('hidden')
            editModal.classList.add('flex')

        })

    })

    function closeEdit(){

        editModal.classList.add('hidden')

    }

</script>
