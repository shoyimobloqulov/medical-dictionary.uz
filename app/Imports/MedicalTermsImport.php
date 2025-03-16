<?php

namespace App\Imports;

use App\Models\Language;
use App\Models\MedicalTerm;
use App\Models\MedicalTermTranslation;
use Maatwebsite\Excel\Concerns\ToModel;

class MedicalTermsImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return MedicalTermTranslation
     */
    public function model(array $row)
    {
        dd($row);
        $language = Language::firstOrCreate([
            'name' => $row['language'],
        ]);

        $medicalTerm = MedicalTerm::firstOrCreate([]);

        return new MedicalTermTranslation([
            'medical_term_id' => $medicalTerm->id,
            'language_id' => $language->id,
            'name' => $row['name'],
            'description' => $row['description'],
        ]);
    }
}
