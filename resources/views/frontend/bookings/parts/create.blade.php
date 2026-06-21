<div id="bookingModal" class="fixed inset-0 z-50 hidden">

    <div id="bookingOverlay" class="absolute inset-0 bg-black/60 backdrop-blur-sm"></div>

    <div class="relative w-full h-full flex items-end sm:items-center justify-center p-4">

        <div class="w-full sm:max-w-3xl bg-white dark:bg-gray-900 rounded-t-3xl sm:rounded-2xl shadow-2xl flex flex-col max-h-[90vh]">

            <div class="flex justify-between items-center px-6 py-4 border-b dark:border-gray-700">
                <h3 class="text-lg font-bold text-gray-800 dark:text-white">
                    إنشاء حجز جديد
                </h3>
                <button id="closeBookingModal" class="text-gray-500 hover:text-red-500 text-xl">✕</button>
            </div>

            <div class="p-6 overflow-y-auto">

                <form method="POST" action="{{ route('dashboard.bookings.store') }}" id="bookingForm" class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @csrf

                    <div class="sm:col-span-2 relative">
                        <label class="text-sm text-gray-500 block mb-1">
                            العميل (ابحث بالاسم، الهاتف، أو جواز السفر)
                        </label>

                        <input type="text"
                               id="clientSearchInput"
                               placeholder="اكتب اسم العميل أو رقمه للبحث..."
                               autocomplete="off"
                               required
                               class="w-full border border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white rounded-xl px-4 py-2 focus:ring-2 focus:ring-blue-500 outline-none">

                        <input type="hidden" name="client_id" id="clientIdField" required>

                        <div id="clientSearchResults"
                             class="absolute left-0 right-0 mt-1 bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl shadow-xl max-h-60 overflow-y-auto hidden z-50 divide-y divide-gray-100 dark:divide-gray-600">
                        </div>
                    </div>

                    <div class="sm:col-span-2">
                        <label class="text-sm text-gray-500">
                            رقم الجواز
                        </label>
                        <input
                            type="text"
                            id="passportNumber"
                            readonly
                            class="w-full border border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white rounded-xl px-4 py-2 bg-gray-50 dark:bg-gray-950">
                    </div>

                    <div class="sm:col-span-2">
                        <label class="text-sm text-gray-500">
                            الرحلة
                        </label>
                        <select
                            name="trip_id"
                            id="tripSelect"
                            required
                            class="w-full border border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white rounded-xl px-4 py-2 focus:ring-2 focus:ring-blue-500">
                            <option value="">اختر الرحلة</option>
                            @foreach($trips as $trip)
                                <option
                                    value="{{ $trip->id }}"
                                    data-purchase="{{ $trip->purchase_price }}"
                                    data-sale="{{ $trip->sale_price }}"
                                    data-currency="{{ $trip->currency->code ?? '' }}">
                                    {{ $trip->from_city }} → {{ $trip->to_city }} | {{ $trip->trip_date }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="sm:col-span-2">
                        <label class="text-sm text-gray-500 mb-2 block">
                            اختر المقعد
                        </label>
                        <input type="hidden" name="seat_number" id="seatNumber">
                        <div id="seatsContainer" class="grid grid-cols-5 sm:grid-cols-6 md:grid-cols-8 gap-2 mt-2"></div>
                    </div>

                    <div>
                        <label class="text-sm text-gray-500">
                            نسبة الخصم %
                        </label>
                        <input
                            type="number"
                            name="discount_percent"
                            value="0"
                            min="0"
                            max="100"
                            class="w-full border border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white rounded-xl px-4 py-2">
                    </div>

                    <div>
                        <label class="text-sm text-gray-500">
                            سعر التكلفة
                        </label>
                        <input
                            type="text"
                            id="purchasePrice"
                            readonly
                            class="w-full border border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white rounded-xl px-4 py-2 bg-gray-50 dark:bg-gray-950">
                    </div>

                    <div>
                        <label class="text-sm text-gray-500">
                            سعر البيع
                        </label>
                        <input
                            type="text"
                            id="salePrice"
                            readonly
                            class="w-full border border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white rounded-xl px-4 py-2 bg-gray-50 dark:bg-gray-950">
                    </div>

                    <div class="sm:col-span-2">
                        <label class="text-sm text-gray-500">
                            العملة
                        </label>
                        <input
                            type="text"
                            id="currencyCode"
                            readonly
                            class="w-full border border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white rounded-xl px-4 py-2 bg-gray-50 dark:bg-gray-950">
                    </div>

                    <div class="sm:col-span-2">
                        <label class="text-sm text-gray-500">
                            دفعة أولية
                        </label>
                        <input
                            type="number"
                            name="payment_amount"
                            value="0"
                            min="0"
                            step="0.01"
                            class="w-full border border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white rounded-xl px-4 py-2">
                    </div>
                </form>
            </div>

            <div class="px-6 py-4 border-t dark:border-gray-700 flex justify-end">
                <button type="submit" form="bookingForm" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-xl font-semibold transition-all">
                    حفظ الحجز
                </button>
            </div>

        </div>
    </div>
</div>
<script>
    // 1. تسجيل دوال الفتح والإغلاق فوراً في نافذة المتصفح (خارج الـ DOMContentLoaded)
    // لضمان استجابة الزر onclick في أي وقت دون أي تأخير
    window.openBookingModal = function() {
        const modal = document.getElementById('bookingModal');
        if(modal) {
            modal.classList.remove('hidden');
        }
    }

    window.closeAndClearModal = function() {
        const modal = document.getElementById('bookingModal');
        if(modal) {
            modal.classList.add('hidden');
        }
        // تفريغ حقول البحث عند الإغلاق لتجنب اللخبطة
        const searchInput = document.getElementById('clientSearchInput');
        const idField = document.getElementById('clientIdField');
        const passportInput = document.getElementById('passportNumber');
        const resultsDiv = document.getElementById('clientSearchResults');

        if(searchInput) searchInput.value = '';
        if(idField) idField.value = '';
        if(passportInput) passportInput.value = '';
        if(resultsDiv) resultsDiv.classList.add('hidden');
    }

    // 2. باقي منطق الأحداث والـ AJAX ينتظر اكتمال تحميل عناصر الصفحة
    document.addEventListener("DOMContentLoaded", function() {

        const closeBtn = document.getElementById('closeBookingModal');
        const overlay = document.getElementById('bookingOverlay');

        // ربط أزرار الإغلاق بالدالة التي أصبحت معرفة بالأعلى
        if(closeBtn) closeBtn.addEventListener('click', window.closeAndClearModal);
        if(overlay) overlay.addEventListener('click', window.closeAndClearModal);

        // ==========================================
        // منطق البحث الحي عن العملاء (AJAX Debounce)
        // ==========================================
        const clientSearchInput = document.getElementById('clientSearchInput');
        const clientIdField = document.getElementById('clientIdField');
        const clientSearchResults = document.getElementById('clientSearchResults');
        const passportInput = document.getElementById('passportNumber');
        let debounceTimer;

        if (clientSearchInput) {
            clientSearchInput.addEventListener('input', function () {
                const query = this.value.trim();
                clientIdField.value = '';
                passportInput.value = '';

                clearTimeout(debounceTimer);

                if (query.length < 2) {
                    clientSearchResults.innerHTML = '';
                    clientSearchResults.classList.add('hidden');
                    return;
                }

                debounceTimer = setTimeout(() => {
                    fetch(`/dashboard/clients/search?search=${encodeURIComponent(query)}`, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        }
                    })
                        .then(response => response.json())
                        .then(data => {
                            clientSearchResults.innerHTML = '';

                            if (data.length === 0) {
                                clientSearchResults.innerHTML = `<div class="p-3 text-sm text-gray-500 dark:text-gray-400">لا توجد نتائج مطابقة</div>`;
                                clientSearchResults.classList.remove('hidden');
                                return;
                            }

                            data.forEach(client => {
                                const div = document.createElement('div');
                                div.className = "p-3 text-sm text-gray-700 dark:text-gray-200 hover:bg-blue-50 dark:hover:bg-gray-600 cursor-pointer transition-colors";

                                let extraInfo = client.phone ? `(${client.phone})` : '';
                                if(client.passport_number) extraInfo += ` - ج: ${client.passport_number}`;

                                div.innerHTML = `<strong>${client.full_name}</strong> <span class="text-xs text-gray-400 block mt-0.5">${extraInfo}</span>`;

                                div.addEventListener('click', function () {
                                    clientSearchInput.value = client.full_name;
                                    clientIdField.value = client.id;
                                    passportInput.value = client.passport_number || "لا يوجد رقم جواز";

                                    clientSearchResults.innerHTML = '';
                                    clientSearchResults.classList.add('hidden');
                                });

                                clientSearchResults.appendChild(div);
                            });

                            clientSearchResults.classList.remove('hidden');
                        })
                        .catch(error => console.error('Error fetching clients:', error));
                }, 300);
            });
        }

        // إغلاق النتائج عند النقر في أي مكان خارج الحقل
        document.addEventListener('click', function (e) {
            if (e.target !== clientSearchInput && e.target !== clientSearchResults) {
                if(clientSearchResults) clientSearchResults.classList.add('hidden');
            }
        });


        // ==========================================
        // مراقبة جلب أسعار وبيانات الرحلات والمقاعد
        // ==========================================
        const tripSelect = document.getElementById("tripSelect");
        const purchase = document.getElementById("purchasePrice");
        const sale = document.getElementById("salePrice");
        const currency = document.getElementById("currencyCode");
        const seatsContainer = document.getElementById("seatsContainer");
        const seatInput = document.getElementById("seatNumber");

        if (tripSelect) {
            tripSelect.addEventListener("change", function(){
                const option = this.options[this.selectedIndex];

                purchase.value = option.dataset.purchase || "";
                sale.value = option.dataset.sale || "";
                currency.value = option.dataset.currency || "";

                const tripId = this.value;
                seatsContainer.innerHTML = "";
                seatInput.value = "";

                if(!tripId) return;

                fetch(`/dashboard/trips/${tripId}/seats`)
                    .then(res => res.json())
                    .then(data => {
                        const total = data.totalSeats;
                        const booked = data.bookedSeats;

                        for(let i=1; i<=total; i++){
                            const seat = document.createElement("button");
                            seat.type = "button";
                            seat.innerText = i;
                            seat.className = "p-2 rounded-lg text-sm border transition-all";

                            if(booked.includes(i)){
                                seat.classList.add("bg-red-200", "text-red-700", "cursor-not-allowed");
                                seat.disabled = true;
                            } else {
                                seat.classList.add("bg-green-100", "hover:bg-green-200", "text-green-800", "dark:bg-green-950/30", "dark:text-green-400");

                                seat.onclick = () => {
                                    document.querySelectorAll("#seatsContainer button").forEach(btn => {
                                        btn.classList.remove("ring-2", "ring-blue-500", "bg-blue-600", "text-white");
                                    });
                                    seat.classList.add("ring-2", "ring-blue-500");
                                    seatInput.value = i;
                                };
                            }
                            seatsContainer.appendChild(seat);
                        }
                    });
            });
        }


        // ==========================================
        // فحص جودة وصحة المدخلات قبل الإرسال Submit
        // ==========================================
        const bookingForm = document.getElementById("bookingForm");
        if (bookingForm) {
            bookingForm.addEventListener("submit", function(e){
                const clientId = document.getElementById("clientIdField").value;
                const trip = document.getElementById("tripSelect").value;
                const seat = document.getElementById("seatNumber").value;

                if(!clientId){
                    alert("يجب اختيار العميل من القائمة المنبثقة للبحث");
                    e.preventDefault();
                    return;
                }
                if(!trip){
                    alert("يجب اختيار الرحلة");
                    e.preventDefault();
                    return;
                }
                if(!seat){
                    alert("يجب اختيار المقعد المخصص للحجز");
                    e.preventDefault();
                    return;
                }
            });
        }

    });

    // ==========================================
    // 3. مراقبة جلب أسعار وبيانات الرحلات والمقاعد (المطور والمعدل)
    // ==========================================
    const tripSelect = document.getElementById("tripSelect");
    const purchase = document.getElementById("purchasePrice");
    const sale = document.getElementById("salePrice");
    const currency = document.getElementById("currencyCode");
    const seatsContainer = document.getElementById("seatsContainer");
    const seatInput = document.getElementById("seatNumber");

    if (tripSelect) {
        tripSelect.addEventListener("change", function(){
            const option = this.options[this.selectedIndex];

            purchase.value = option.dataset.purchase || "";
            sale.value = option.dataset.sale || "";
            currency.value = option.dataset.currency || "";

            const tripId = this.value;
            seatsContainer.innerHTML = "";
            seatInput.value = ""; // تصفير المقعد المحفوظ مسبقاً

            if(!tripId) return;

            // إظهار رسالة تحميل ناعمة للمقاعد لحين استجابة السيرفر
            seatsContainer.innerHTML = `<div class="col-span-full text-xs text-blue-500 animate-pulse">🔄 جاري جلب مخطط المقاعد الفعلي...</div>`;

            fetch(`/trips/${tripId}/seats`)
                .then(res => res.json())
                .then(data => {
                    seatsContainer.innerHTML = ""; // تفريغ حقل التحميل

                    // تأمين جلب البيانات سواء كانت الكلمات تبدأ بأحرف كابيتال أو صغيرة
                    const total = data.totalSeats || data.totalseats || data.total_seats || 0;
                    const booked = data.bookedSeats || data.bookedseats || data.booked_seats || [];

                    if (total === 0) {
                        seatsContainer.innerHTML = `<div class="col-span-full text-xs text-amber-500">⚠ لم يتم تحديد عدد مقاعد لهذا الباص بعد.</div>`;
                        return;
                    }

                    for(let i = 1; i <= total; i++){
                        const seat = document.createElement("button");
                        seat.type = "button";
                        seat.innerText = i;
                        // كلاسات التصميم الموحدة
                        seat.className = "p-2.5 rounded-xl text-xs font-mono font-bold border transition-all duration-200 active:scale-95";

                        // فحص ما إذا كان المقعد محجوزاً مسبقاً (تأمين قراءة الرقم كـ Integer)
                        if(booked.includes(i) || booked.includes(String(i))){
                            seat.classList.add("bg-red-50", "text-red-500", "border-red-200", "cursor-not-allowed", "dark:bg-red-950/20", "dark:text-red-400", "dark:border-red-900/50");
                            seat.disabled = true;
                        } else {
                            // المقاعد المتاحة للشراء
                            seat.classList.add("bg-emerald-50/50", "text-emerald-700", "border-emerald-200", "hover:bg-emerald-600", "hover:text-white", "hover:border-emerald-600", "dark:bg-emerald-950/20", "dark:text-emerald-400", "dark:border-emerald-900/40");

                            // حدث النقر واختيار المقعد المطور
                            seat.onclick = () => {
                                // 1. إعادة الألوان الافتراضية لكافة المقاعد المتاحة الأخرى وتطهيرها
                                document.querySelectorAll("#seatsContainer button:not([disabled])").forEach(btn => {
                                    btn.classList.remove("bg-blue-600", "text-white", "border-blue-600", "ring-4", "ring-blue-500/20");
                                    btn.classList.add("bg-emerald-50/50", "text-emerald-700", "border-emerald-200");
                                });

                                // 2. تطبيق هوية الخيار النشط على المقعد الذي تم نقره حالياً
                                seat.classList.remove("bg-emerald-50/50", "text-emerald-700", "border-emerald-200");
                                seat.classList.add("bg-blue-600", "text-white", "border-blue-600", "ring-4", "ring-blue-500/20");

                                // 3. حقن رقم المقعد في الحقل المخفي فوراً لضمان جاهزيته للـ Form Submit
                                seatInput.value = i;
                            };
                        }
                        seatsContainer.appendChild(seat);
                    }
                })
                .catch(error => {
                    console.error('Error fetching seats:', error);
                    seatsContainer.innerHTML = `<div class="col-span-full text-xs text-red-500">⚠ فشل الاتصال بالسيرفر لجلب المقاعد.</div>`;
                });
        });
    }
</script>
