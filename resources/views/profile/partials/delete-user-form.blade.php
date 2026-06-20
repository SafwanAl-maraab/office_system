<section class="space-y-8">
    <header class="flex flex-col sm:flex-row items-start gap-5">
        <div class="w-14 h-14 rounded-2xl bg-rose-100 dark:bg-rose-500/20 text-rose-600 dark:text-rose-400 flex items-center justify-center shrink-0 shadow-inner">
            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
        </div>
        <div>
            <h2 class="text-xl font-extrabold text-slate-900 dark:text-white">
                حذف الحساب
            </h2>
            <p class="mt-2 text-sm text-slate-600 dark:text-slate-400 leading-relaxed max-w-xl">
                بمجرد حذف حسابك، سيتم حذف جميع موارده وبياناته نهائياً. قبل حذف الحساب، يرجى تنزيل أي بيانات أو معلومات ترغب في الاحتفاظ بها.
            </p>
        </div>
    </header>

    <div class="flex sm:rtl:mr-19 sm:ltr:ml-19">
        <button
            x-data=""
            x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
            class="px-8 py-3.5 rounded-2xl font-bold text-white bg-gradient-to-r from-red-500 to-rose-600 hover:from-red-600 hover:to-rose-700 focus:outline-none focus:ring-4 focus:ring-rose-500/30 transform hover:-translate-y-1 shadow-[0_10px_20px_-10px_rgba(244,63,94,0.5)] transition-all duration-300 flex items-center gap-2"
        >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            <span>حذف الحساب</span>
        </button>
    </div>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-8 bg-white dark:bg-slate-900 rounded-2xl relative overflow-hidden">
            @csrf
            @method('delete')

            <div class="absolute top-0 rtl:right-0 ltr:left-0 w-full h-2 bg-gradient-to-r from-red-500 to-rose-600"></div>

            <div class="mt-4 mb-6 text-center sm:text-start rtl:sm:text-right ltr:sm:text-left">
                <div class="w-16 h-16 rounded-full bg-red-100 dark:bg-red-500/20 text-red-600 dark:text-red-400 flex items-center justify-center mx-auto sm:mx-0 mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                </div>
                
                <h2 class="text-2xl font-bold text-slate-900 dark:text-white">
                    هل أنت متأكد من أنك تريد حذف حسابك؟
                </h2>

                <p class="mt-3 text-sm text-slate-600 dark:text-slate-400 leading-relaxed">
                    بمجرد حذف حسابك، سيتم حذف جميع البيانات نهائياً. يرجى إدخال كلمة المرور الخاصة بك لتأكيد رغبتك في حذف الحساب.
                </p>
            </div>

            <div class="mt-6">
                <label for="password" class="sr-only">كلمة المرور</label>
                
                <div class="relative group">
                    <div class="absolute inset-y-0 ltr:left-0 rtl:right-0 flex items-center rtl:pr-4 ltr:pl-4 pointer-events-none text-slate-400 group-focus-within:text-rose-500 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                    </div>
                    
                    <input
                        id="password"
                        name="password"
                        type="password"
                        class="w-full sm:w-3/4 rtl:pr-12 rtl:pl-4 ltr:pl-12 ltr:pr-4 py-3.5 rounded-2xl bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-white/10 text-slate-900 dark:text-white placeholder-slate-400 focus:bg-white dark:focus:bg-white/10 focus:border-rose-500 focus:ring-1 focus:ring-rose-500 transition-all duration-300 outline-none"
                        placeholder="أدخل كلمة المرور للتأكيد"
                    />
                </div>

                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2 text-red-500 text-sm" />
            </div>

            <div class="mt-8 flex flex-col-reverse sm:flex-row items-center justify-end gap-3">
                <button 
                    type="button" 
                    x-on:click="$dispatch('close')"
                    class="w-full sm:w-auto px-6 py-3.5 rounded-2xl font-bold text-slate-600 dark:text-slate-300 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 transition-all duration-300 text-center"
                >
                    إلغاء
                </button>

                <button 
                    type="submit"
                    class="w-full sm:w-auto px-8 py-3.5 rounded-2xl font-bold text-white bg-gradient-to-r from-red-600 to-rose-500 hover:from-red-700 hover:to-rose-600 focus:outline-none focus:ring-4 focus:ring-rose-500/30 transform hover:-translate-y-1 shadow-[0_10px_20px_-10px_rgba(244,63,94,0.5)] transition-all duration-300 text-center flex justify-center items-center gap-2"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                    <span>تأكيد الحذف</span>
                </button>
            </div>
        </form>
    </x-modal>
</section>