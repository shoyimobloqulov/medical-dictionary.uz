<?php

namespace App\Exports;

use App\Models\Language;
use App\Models\MedicalTerm;
use Maatwebsite\Excel\Concerns\FromCollection;

class MedicalTermsExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $languages = Language::all();

        return MedicalTerm::with('translations.language')->get()->map(function ($term) use ($languages) {
            $row = [
                'ID' => $term->id,
            ];

            foreach ($languages as $language) {
                $translation = $term->translations->where('language_id', $language->id)->first();
                $row[$language->name] = $translation ? $translation->name : '-';
            }

            return $row;
        });
    }
}
