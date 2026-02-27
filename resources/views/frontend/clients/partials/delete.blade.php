<!-- =========================
     CLIENT DELETE MODAL
========================== -->

<div id="deleteClientModal" class="fixed inset-0 z-50 hidden">

    <!-- Overlay -->
    <div id="deleteClientOverlay"
         class="absolute inset-0 bg-black/60 backdrop-blur-sm">
    </div>

    <!-- Wrapper -->
    <div class="relative w-full h-full flex items-end sm:items-center justify-center">

        <!-- Card -->
        <div class="w-full sm:max-w-md h-auto bg-white dark:bg-gray-900 rounded-t-3xl sm:rounded-3xl shadow-2xl p-8 space-y-6">

            <!-- Icon -->
            <div class="flex justify-center">
                <div class="h-16 w-16 rounded-full bg-red-100 flex items-center justify-center text-red-600 text-3xl">
                    !
                </div>
            </div>

            <!-- Title -->
            <div class="text-center space-y-2">
                <h3 class="text-xl font-bold">
                    تأكيد الحذف
                </h3>

                <p class="text-gray-500 text-sm">
                    هل أنت متأكد أنك تريد حذف العميل:
                </p>

                <p id="deleteClientName"
                   class="font-semibold text-gray-800 dark:text-gray-200">
                </p>

                <p class="text-red-500 text-xs">
                    لا يمكن التراجع بعد الحذف
                </p>
            </div>

            <!-- Buttons -->
            <div class="flex gap-4">

                <button id="cancelDeleteClient"
                        class="flex-1 py-3 rounded-2xl bg-gray-200 dark:bg-gray-700 hover:opacity-80 transition">
                    إلغاء
                </button>

                <form id="deleteClientForm"
                      method="POST"
                      class="flex-1">
                    @csrf
                    @method('DELETE')

                    <button id="confirmDeleteClient"
                            type="submit"
                            class="w-full py-3 rounded-2xl bg-red-600 text-white font-semibold hover:scale-[1.02] transition">
                        حذف نهائي
                    </button>
                </form>

            </div>

        </div>

    </div>
</div>

<!-- =========================
     DELETE SCRIPT
========================== -->

<script>
    document.addEventListener('DOMContentLoaded', function () {

        const modal = document.getElementById('deleteClientModal');
        const overlay = document.getElementById('deleteClientOverlay');
        const cancelBtn = document.getElementById('cancelDeleteClient');
        const form = document.getElementById('deleteClientForm');
        const nameBox = document.getElementById('deleteClientName');
        const confirmBtn = document.getElementById('confirmDeleteClient');

        function openModal(id, name) {
            form.action = "/dashboard/clients/" + id;
            nameBox.innerText = name;

            modal.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        }

        function closeModal() {
            modal.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }

        // زر حذف من الكروت
        document.querySelectorAll('[data-delete-client]').forEach(btn => {
            btn.addEventListener('click', function () {
                const id = this.dataset.id;
                const card = this.closest('.bg-white, .dark\\:bg-gray-900');
                const name = card.querySelector('h3').innerText;

                openModal(id, name);
            });
        });

        // إغلاق
        cancelBtn.addEventListener('click', closeModal);
        overlay.addEventListener('click', closeModal);

        document.addEventListener('keydown', function(e){
            if (e.key === 'Escape') closeModal();
        });

        // منع الضغط المتكرر
        form.addEventListener('submit', function(){
            confirmBtn.innerText = "جاري الحذف...";
            confirmBtn.disabled = true;
        });

    });
</script>
