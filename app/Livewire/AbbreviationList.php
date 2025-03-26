<?php

namespace App\Livewire;

use App\Models\Abbreviation;
use Livewire\Component;
use Livewire\WithPagination;

class AbbreviationList extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 9;

    protected $queryString = ['search'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function loadMore()
    {
        $this->perPage += 9; // Har bosishda +10 qo'shish
    }


    public function render()
    {
        $abbreviations = Abbreviation::where('title', 'like', "%{$this->search}%")
            ->orderBy('title')
            ->take($this->perPage)
            ->get();
        return view('livewire.abbreviation-list', compact('abbreviations'));
    }
}
