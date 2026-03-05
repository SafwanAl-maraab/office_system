<!-- CREATE TRIP GROUP -->

<div id="createTripModal"
class="hidden fixed inset-0 bg-black/60 flex items-center justify-center z-50">

<div class="bg-white dark:bg-gray-900 w-full max-w-xl rounded-2xl p-6">

<h2 class="text-lg font-bold mb-6 text-gray-800 dark:text-white">

إنشاء حملة

</h2>


<form method="POST" action="{{ route('trip-groups.store') }}">

@csrf

<div class="grid grid-cols-2 gap-4">

<input type="text" name="name"
placeholder="اسم الحملة"
class="input-style">

<input type="number" name="total_seats"
placeholder="عدد المقاعد"
class="input-style">

<input type="date" name="departure_date"
class="input-style">

<input type="date" name="return_date"
class="input-style">

</div>

<div class="flex justify-end gap-3 mt-6">

<button type="button" onclick="closeCreateTripModal()"
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