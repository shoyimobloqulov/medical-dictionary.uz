<?php

namespace App\Http\Controllers;

use App\Models\Language;
use App\Models\MedicalTerm;
use App\Models\MedicalTermTranslation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;

class WordController extends Controller
{
    public function readWord()
    {

        $raw = MedicalTermTranslation::where('id', '>=', 27)
            ->where('language_id', 3)
            ->get();

        foreach ($raw as $item) {
            // Yangi medical_term_id
            $newMedicalTermId = $item->medical_term_id - 1;

            // Agar yangi medical_term_id `medical_terms` jadvalida mavjud bo'lsa, update qilamiz
            if (MedicalTerm::where('id', $newMedicalTermId)->exists()) {
                $item->medical_term_id = $newMedicalTermId;
                $item->save();
            }
        }

        dd($raw);

//        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
//
//        MedicalTermTranslation::truncate();
//        MedicalTerm::truncate();
//
//        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
//
//        dd(1);//sss

        $filePath = public_path('ru.xlsx');
        $phpWord = IOFactory::load($filePath);
        $text = '';


        if (!file_exists($filePath)) {
            return response()->json(['error' => 'Fayl topilmadi!'], 404);
        }
        $spreadsheet = IOFactory::load($filePath);
        $sheet = $spreadsheet->getActiveSheet();

        $datax = $sheet->toArray(null, true, true, true);

        $finalArray = [];

        foreach ($datax as $row) {
            if (!isset($row['A']) || empty($row['A'])) {
                continue;
            }

            $splitData = explode("\n", $row['A']);

            foreach ($splitData as $data) {
                $data = trim($data);
                if (empty($data)) continue;

                preg_match('/^(.+?)\s*\((.*?)\)\s*–\s*(.+)$/', $data, $matches);

                if ($matches) {
                    $word = trim($matches[1]) . " (" . trim($matches[2]) . ")";
                    $desc = trim($matches[3]);
                } else {
                    $parts = explode(" – ", $data, 2);
                    $word = trim($parts[0]);
                    $desc = isset($parts[1]) ? trim($parts[1]) : "";
                }

                $finalArray[] = [
                    "word" => $word,
                    "desc" => $word . " " . $desc
                ];
            }
        }

        $lang_id = 2;


        $medicals = MedicalTerm::all();

        foreach ($medicals as $index => $medical) {
            MedicalTermTranslation::create([
                'medical_term_id' => $medical->id,
                'language_id' => $lang_id,
                'name' => $finalArray[$index]['word'],
                'description' => $finalArray[$index]['desc']
            ]);
        }

        return response()->json($finalArray);
    }
}
