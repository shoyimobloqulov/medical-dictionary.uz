<div class="g-gradient-to-b from-blue-50 to-white p-3">
    <div class="container mx-auto">
        <!-- Header -->
        <div class="flex items-center justify-center gap-2 mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                 stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                 class="w-8 h-8 text-blue-600 lucide lucide-languages">
                <path d="m5 8 6 6"/>
                <path d="m4 14 6-6 2-3"/>
                <path d="M2 5h12"/>
                <path d="M7 2h1"/>
                <path d="m22 22-5-10-5 10"/>
                <path d="M14 18h6"/>
            </svg>
            <h1 class="text-xl font-bold text-gray-800">МЕДИЦИНСКИЙ НАУЧНЫЙ СЛОВАРЬ</h1>
        </div>

        <!-- Translation Section -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
            <div class="flex items-center gap-4 mb-6">
                <select wire:model="fromLang" class="w-full p-3 border rounded-lg">
                    @foreach($languages as $language)
                    <option value="{{ $language->id }}">{{ $language->name }}</option>
                    @endforeach
                </select>

                <button wire:click="swapLanguages" class="p-2 rounded-full bg-blue-50">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                         class="lucide lucide-arrow-right-left w-6 h-6 text-blue-600">
                        <path d="m16 3 4 4-4 4"/>
                        <path d="M20 7H4"/>
                        <path d="m8 21-4-4 4-4"/>
                        <path d="M4 17h16"/>
                    </svg>
                </button>

                <select wire:model="toLang" class="w-full p-3 border rounded-lg">
                    @foreach($languages as $language)
                    <option value="{{ $language->id }}">{{ $language->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="grid md:grid-cols-2 gap-6">
                <textarea wire:model.live="fromText" placeholder="Enter text..."
                          class="w-full p-4 border rounded-lg h-32"></textarea>

                <div class="relative">
                    <textarea class="w-full p-4 bg-gray-50 border rounded-lg h-32" readonly>{{ trim(str_replace('&nbsp;',
                        ' ', strip_tags(html_entity_decode($toText)))) }}
                    </textarea>


                    @if ($isTranslating)
                    <div class="absolute inset-0 flex items-center justify-center bg-gray-50/80 rounded-lg">
                        <div
                            class="w-6 h-6 border-2 border-blue-600 border-t-transparent rounded-full animate-spin"></div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Alphabet Navigation -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
            <!-- Language Select -->
            <div class="flex justify-center items-center">
                <select wire:model="selectedLanguage" wire:change="updateLanguage($event.target.value)"
                        class="px-4 py-2 border rounded-lg my-3 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                    @foreach($languages as $language)
                    <option value="{{ $language['code'] }}">{{ $language['name'] }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex flex-wrap justify-center gap-2">

                @foreach ($letters as $letter)
                <button wire:click="setLetter('{{ $letter }}')"
                        class="px-4 py-2 rounded-lg text-lg {{ $selectedLetter === $letter ? 'bg-blue-600 text-white' : 'bg-blue-50 text-blue-600 hover:bg-blue-100' }}">
                    {{ $letter }}
                </button>
                @endforeach
            </div>
        </div>

        <!-- Dictionary Words -->
        <div wire:init="loadWords" class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-3xl font-bold text-gray-800 border-2 border-blue-500 shadow-lg rounded-lg px-6 py-3 inline-block">
                    {{ $selectedLetter }}
                </h2>


                <button wire:click="toggleSortOrder"
                        class="flex items-center gap-2 px-4 py-2 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                         class="lucide lucide-arrow-down-a-z w-4 h-4">
                        <path d="m3 16 4 4 4-4"/>
                        <path d="M7 20V4"/>
                        <path d="M20 8h-5"/>
                        <path d="M15 10V6.5a2.5 2.5 0 0 1 5 0V10"/>
                        <path d="M15 14h5l-5 6h5"/>
                    </svg>
                    Сортировать ({{ strtoupper($sortOrder) }})
                </button>
            </div>

            @if (!$isLoaded)
            <div class="flex justify-center items-center h-32">
                <div class="w-6 h-6 border-4 border-blue-600 border-t-transparent rounded-full animate-spin"/>
            </div>
            @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 min-h-[400px]">
                @foreach ($words as $word)
                <div class="border rounded-lg p-4 bg-white hover:border-blue-300">
                    <div class="flex justify-between items-start mb-2">
                        <h3 class="text-lg font-semibold">{{ $word->word }}</h3>
                        <span class="px-2 py-1 bg-blue-50 text-blue-600 text-sm rounded-full">
                            {{ $word->language->name }}
                        </span>
                    </div>
                    <p class="text-blue-600 font-medium mb-2">{{ $word->name }}</p>
                    <p class="text-gray-600 text-sm mb-3">{!! $word->description !!}</p>
                </div>
                @endforeach
            </div>

            <div class="flex justify-center items-center gap-2 mt-4">
                {{ $words->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
