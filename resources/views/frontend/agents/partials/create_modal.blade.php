<div
id="createAgentModal"
class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">

<div class="bg-white dark:bg-gray-800 p-6 rounded-xl w-96">

<h2 class="text-lg font-bold mb-4">

إضافة وكيل جديد

</h2>

<form method="POST"
action="{{ route('agents.store') }}">

@csrf

<input
name="name"
placeholder="اسم الوكيل"
class="w-full border rounded-lg p-2 mb-3 dark:bg-gray-900 dark:border-gray-700">


<input
name="phone"
placeholder="رقم الهاتف"
class="w-full border rounded-lg p-2 mb-3 dark:bg-gray-900 dark:border-gray-700">


<input
name="country"
placeholder="الدولة"
class="w-full border rounded-lg p-2 mb-3 dark:bg-gray-900 dark:border-gray-700">


<input
name="city"
placeholder="المدينة"
class="w-full border rounded-lg p-2 mb-3 dark:bg-gray-900 dark:border-gray-700">


<div class="flex gap-3">

<button
class="bg-blue-600 text-white px-4 py-2 rounded-lg">

حفظ

</button>

<button
type="button"
onclick="document.getElementById('createAgentModal').classList.add('hidden')"
class="bg-gray-400 text-white px-4 py-2 rounded-lg">

إلغاء

</button>

</div>

</form>

</div>

</div>