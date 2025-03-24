<?php

namespace App\Livewire;

use App\Models\MedicalTermTranslation;
use App\Models\Language;
use Livewire\Component;
use Livewire\WithPagination;

class Dictionary extends Component
{
    use WithPagination;

    public $fromLang;
    public $toLang;
    public $fromText = '';
    public $toText = '';
    public $selectedLetter = 'A';
    public $sortOrder = 'asc';
    public $isTranslating = false;
    public $isLoaded = false;
    public $search = '';
    public $languages;
    public $selectedLanguage = 'en';

    protected $paginationTheme = 'tailwind';

    public $alphabets = [
        'en' => [],
        'ru' => ['А', 'Б', 'В', 'Г', 'Д', 'Е', 'Ё', 'Ж', 'З', 'И', 'Й', 'К', 'Л', 'М', 'Н', 'О', 'П', 'Р', 'С', 'Т', 'У', 'Ф', 'Х', 'Ц', 'Ч', 'Ш', 'Щ', 'Ъ', 'Ы', 'Ь', 'Э', 'Ю', 'Я'],
        'uz' => ['A', 'B', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'X', 'Y', 'Z', 'Sh', 'Ch', 'Ng', 'Oʻ', 'Gʻ'],
    ];


    public function mount()
    {
        $this->fromLang = Language::where('code', 'uz')->first()->id ?? null;
        $this->toLang = Language::where('code', 'en')->first()->id ?? null;
        $this->words = collect();

        $this->alphabets['en'] = range('A','Z');

        $this->languages = Language::all();
    }

    public function updateLanguage($language)
    {
        $this->selectedLanguage = $language;

        $this->selectedLetter = $this->alphabets[$language][0] ?? 'A';
    }


    public function updatedFromText()
    {
        $this->isTranslating = true;

        if (!$this->fromText) {
            $this->toText = '';
            $this->isTranslating = false;
            return;
        }

        $translation = MedicalTermTranslation::whereHas('language', function ($query) {
            $query->where('id', $this->toLang);
        })
            ->where('name', 'LIKE', '%' . $this->fromText . '%')
            ->first();

        $this->toText = $translation->name ?? 'Перевод не найден';
        $this->isTranslating = false;
    }

    public function swapLanguages()
    {
        [$this->fromLang, $this->toLang] = [$this->toLang, $this->fromLang];
        [$this->fromText, $this->toText] = [$this->toText, $this->fromText];
    }

    public function setLetter($letter)
    {
        $this->selectedLetter = $letter;
        $this->search = ''; // Qidiruvni tozalash
        $this->resetPage();
        $this->loadWords();
    }

    public function toggleSortOrder()
    {
        $this->sortOrder = $this->sortOrder === 'asc' ? 'desc' : 'asc';
        $this->loadWords();
    }

    public function loadWords()
    {
        $this->isLoaded = false;

        $query = MedicalTermTranslation::where('language_id', 2);

        if (!empty($this->search)) {
            $query->where('name', 'LIKE', '%' . $this->search . '%');
        } else {
            $query->where('name', 'LIKE', $this->selectedLetter . '%');
        }

        $this->words = $query->orderBy('name', $this->sortOrder)->paginate(6);

        $this->isLoaded = true;
    }

    public function updatedSearch()
    {
        $this->resetPage();
        $this->loadWords();
    }

    public function render()
    {
        $words = MedicalTermTranslation::where('language_id', Language::where('code', $this->selectedLanguage)->first()->id)
            ->where(function ($query) {
                if (!empty($this->search)) {
                    $query->where('name', 'LIKE', '%' . $this->search . '%');
                } else {
                    $query->where('name', 'LIKE', $this->selectedLetter . '%');
                }
            })
            ->orderBy('name', $this->sortOrder)
            ->paginate(6);

        return view('livewire.dictionary', [
            'words' => $words,
            'letters' => $this->alphabets[$this->selectedLanguage],
        ]);
    }

}
