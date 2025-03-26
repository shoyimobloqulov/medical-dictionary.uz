<div class="max-w-full mx-auto p-4">
    <h1 class="text-center text-2xl my-2">СОКРАЩЕНИЯ СПЕЦИАЛЬНЫХ ТЕРМИНОВ </h1>
    <div class="mb-4 max-w-2xl mx-auto">
        <input
            type="text"
            wire:model.live="search"
            placeholder="Qidirish..."
            class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring focus:ring-blue-200"
        />
    </div>

    <!-- Responsive grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
        @foreach($abbreviations as $abbr)
        <div class="p-4 bg-white shadow rounded-lg border hover:shadow-md transition">
            <h2 class="text-lg font-semibold text-gray-900">{{ $abbr->title }}</h2>
            <p class="text-gray-600">{{ $abbr->description }}</p>
        </div>
        @endforeach
    </div>

    <div class="text-center mt-4">
        <button
            wire:click="loadMore"
            wire:loading.attr="disabled"
            class="px-4 py-2 bg-blue-600 text-white rounded-lg shadow hover:bg-blue-700"
        >
            Перезагрузить...
        </button>
    </div>
</div>
