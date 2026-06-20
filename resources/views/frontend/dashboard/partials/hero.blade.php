<div
    class="relative overflow-hidden
           rounded-3xl
           bg-gradient-to-r
           from-blue-600
           via-indigo-600
           to-purple-700
           shadow-2xl">

    {{-- خلفية زخرفية --}}
    <div
        class="absolute inset-0 opacity-10">

        <div
            class="absolute -top-20 -right-20
                   w-72 h-72
                   bg-white rounded-full">
        </div>

        <div
            class="absolute -bottom-20 -left-20
                   w-96 h-96
                   bg-white rounded-full">
        </div>

    </div>

    <div
        class="relative
               p-6 md:p-10">

        <div
            class="flex flex-col
                   xl:flex-row
                   xl:items-center
                   xl:justify-between
                   gap-8">

            {{-- بيانات المكتب --}}
            <div
                class="flex items-center gap-5">

                <div
                    class="w-24 h-24
                           rounded-3xl
                           bg-white/20
                           backdrop-blur-md
                           flex items-center
                           justify-center
                           overflow-hidden
                           border border-white/20">

                    @if(
                        !empty($officeInfo?->logo)
                    )

                        <img
                            src="{{ asset('storage/'.$officeInfo->logo) }}"
                            alt="logo"
                            class="w-full h-full object-cover">

                    @else

                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="1.5"
                            stroke="currentColor"
                            class="w-12 h-12 text-white">

                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M3 21h18M5 21V7l7-4 7 4v14" />

                        </svg>

                    @endif

                </div>

                <div>

                    <h1
                        class="text-3xl md:text-4xl
                               font-black
                               text-white">

                        {{ $officeInfo?->office_name }}

                    </h1>

                    <p
                        class="text-white/80 mt-2">

                        📍 {{ $branchName ?? 'المركز ...' }}

                    </p>

                    @if(
                        !empty(
                            $officeInfo?->short_description
                        )
                    )

                        <p
                            class="text-white/70
                                   mt-3
                                   max-w-2xl">

                            {{ $officeInfo->short_description }}

                        </p>

                    @endif

                </div>

            </div>

            {{-- معلومات جانبية --}}
            <div
                class="grid
                       grid-cols-2
                       gap-4
                       min-w-[320px]">

                <div
                    class="bg-white/10
                           backdrop-blur-md
                           rounded-2xl
                           p-4
                           border border-white/10">

                    <div
                        class="text-white/70
                               text-sm">

                        التاريخ

                    </div>

                    <div
                        class="text-white
                               font-bold
                               mt-2">

                        {{ now()->format('Y-m-d') }}

                    </div>

                </div>

                <div
                    class="bg-white/10
                           backdrop-blur-md
                           rounded-2xl
                           p-4
                           border border-white/10">

                    <div
                        class="text-white/70
                               text-sm">

                        الوقت

                    </div>

                    <div
                        class="text-white
                               font-bold
                               mt-2">

                        {{ now()->format('H:i') }}

                    </div>

                </div>

                <div
                    class="bg-white/10
                           backdrop-blur-md
                           rounded-2xl
                           p-4
                           border border-white/10">

                    <div
                        class="text-white/70
                               text-sm">

                        الهاتف

                    </div>

                    <div
                        class="text-white
                               font-bold
                               mt-2">

                        {{ $officeInfo?->primary_phone ?? '-' }}

                    </div>

                </div>

                <div
                    class="bg-white/10
                           backdrop-blur-md
                           rounded-2xl
                           p-4
                           border border-white/10">

                    <div
                        class="text-white/70
                               text-sm">

                        البريد

                    </div>

                    <div
                        class="text-white
                               font-bold
                               mt-2 truncate">

                        {{ $officeInfo?->email ?? '-' }}

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>
