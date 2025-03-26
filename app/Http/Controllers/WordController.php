<?php

namespace App\Http\Controllers;

use App\Models\Abbreviation;
use App\Models\Language;
use App\Models\MedicalTerm;
use App\Models\MedicalTermTranslation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;

class WordController extends Controller
{
    public function readWord()
    {
        $check = $this->check("hard.xlsx");

        return response()->json([
            count($check['A']),
            count($check['B']),
            count($check['C'])
        ]);
    }

    public function check($file): array
    {
        $filePath = public_path($file);

        if (!file_exists($filePath)) {
            return response()->json(['error' => 'Fayl topilmadi!'], 404);
        }

        $spreadsheet = IOFactory::load($filePath);
        $sheet = $spreadsheet->getActiveSheet();
        $datax = $sheet->toArray(null, true, true, true);

        $finalArrayA = [];
        $finalArrayB = [];
        $finalArrayC = [];

        foreach ($datax as $row) {
            // "A" ustuni bo'yicha ishlash
            if (isset($row['A']) && !empty($row['A'])) {
                $splitDataA = explode("\n", $row['A']);
                foreach ($splitDataA as $data) {
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

                    $finalArrayA[] = [
                        "word" => $word,
                        "desc" => $word . " " . $desc
                    ];
                }
            }

            // "B" ustuni bo'yicha ishlash
            if (isset($row['B']) && !empty($row['B'])) {
                $splitDataB = explode("\n", $row['B']);
                foreach ($splitDataB as $data) {
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

                    $finalArrayB[] = [
                        "word" => $word,
                        "desc" => $word . " " . $desc
                    ];
                }
            }

            if (isset($row['C']) && !empty($row['C'])) {
                $splitDataC = explode("\n", $row['C']);
                foreach ($splitDataC as $data) {
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

                    $finalArrayC[] = [
                        "word" => $word,
                        "desc" => $word . " " . $desc
                    ];
                }
            }
        }

        return [
            'A' => $finalArrayA,
            'B' => $finalArrayB,
            'C' => $finalArrayC
        ];
    }


//    public function abb()
//    {
//        $abbreviations = [
//            ['title' => 'ACS', 'description' => 'Antibody-combining site – Антителокомбинирующий участок'],
//            ['title' => 'ADCC', 'description' => 'Antibody-dependent cellular cytotoxicity – Антителозависимая клеточная цитотоксичность'],
//            ['title' => 'AFP', 'description' => 'Alpha-fetoprotein – Альфа-фетопротеин'],
//            ['title' => 'AICD', 'description' => 'Activation-induced cell death – Активация индуцированной клеточной смерти'],
//            ['title' => 'AIDS', 'description' => 'Acquired immunodeficiency syndrome – Синдром приобретенного иммунодефицита'],
//            ['title' => 'ALG', 'description' => 'Antilymphocyte globulin – Антилимфоцитарный глобулин'],
//            ['title' => 'ALS', 'description' => 'Anti-lymphocyte serum – Антилимфоцитарная сыворотка'],
//            ['title' => 'APC', 'description' => 'Antigen-presenting cell – Антигенпрезентирующая клетка'],
//            ['title' => 'B', 'description' => 'Basophil – Базофил'],
//            ['title' => 'B lymphocyte', 'description' => 'Bursa-derived lymphocyte – В-лимфоцит'],
//            ['title' => 'BALT', 'description' => 'Bronchus-associated lymphoreticular tissue – Лимфоретикулярная ткань бронхов'],
//            ['title' => 'BCG', 'description' => 'Bacillus Calmette-Guerin – Бацилла Кальмета-Герена'],
//            ['title' => 'CFU-S', 'description' => 'Splenic colony-forming unit – Колониеобразующая единица в селезенке (КОЕс)'],
//            ['title' => 'CGD', 'description' => 'Chronic granulomatous disease – Хронический грануломатоз'],
//            ['title' => 'CID', 'description' => 'Combined immunodeficiency – Комбинированный иммунодефицит'],
//            ['title' => 'CMC', 'description' => 'Complement-mediated cytotoxicity – Цитотоксичность, опосредованная комплементом'],
//            ['title' => 'CMI', 'description' => 'T-cell mediated immunity – Иммунитет, обусловленный T-клетками'],
//            ['title' => 'CMIS', 'description' => 'Common mucosal immune system – Общая иммунная система слизистой'],
//            ['title' => 'CML', 'description' => 'Chronic myeloid leukemia – Хронический миелолейкоз'],
//            ['title' => 'CMLs', 'description' => 'Cell-mediated lympholysis – Лизис, обусловленный лимфоцитами'],
//            ['title' => 'CMV', 'description' => 'Cytomegalovirus – Цитомегаловирус'],
//            ['title' => 'CR', 'description' => 'Complement receptor – Рецептор для комплемента'],
//            ['title' => 'CTL', 'description' => 'Cytotoxic T lymphocyte – Цитотоксический Т-лимфоцит'],
//            ['title' => 'DTH', 'description' => 'Delayed-type hypersensitivity – Гиперчувствительность замедленного типа'],
//            ['title' => 'EBV', 'description' => 'Epstein-Barr virus – Вирус Эпштейн-Барра'],
//            ['title' => 'EC', 'description' => 'Endothelial cell – Эндотелиальная клетка'],
//            ['title' => 'ECM', 'description' => 'Extracellular matrix – Межклеточный матрикс'],
//            ['title' => 'EDN', 'description' => 'Eosinophyl-derived neurotoxin – Нейротоксин, продуцируемый эозинофилами'],
//            ['title' => 'ELISA', 'description' => 'Enzyme-linked immunosorbent assay – Иммуноферментативный анализ'],
//            ['title' => 'ER', 'description' => 'Endoplasmic reticulum – Эндоплазматический ретикулум'],
//            ['title' => 'ES cell', 'description' => 'Embryonic stem cell – Эмбриональная стволовая клетка'],
//            ['title' => 'F', 'description' => 'Fibroblast – Фибробласт'],
//            ['title' => 'Fab', 'description' => 'Antigen-binding fragment (monovalent) – Фрагмент, связывающий антиген, моновалентный'],
//            ['title' => 'F(ab)2', 'description' => 'Antigen-binding fragment (bivalent) – Фрагмент, связывающий антиген, бивалентный'],
//            ['title' => 'GAG', 'description' => 'Glycosaminoglycan – Гликозаминогликан'],
//            ['title' => 'GALT', 'description' => 'Gut-associated lymphoid tissues – Лимфоидная ткань желудочно-кишечного тракта'],
//            ['title' => 'GC', 'description' => 'Germinal center – Центр размножения'],
//            ['title' => 'HAS', 'description' => 'Human serum albumin – Сывороточный альбумин человека'],
//            ['title' => 'HAV', 'description' => 'Hepatitis A virus – Вирус гепатита A'],
//            ['title' => 'HBV', 'description' => 'Hepatitis B virus – Вирус гепатита В'],
//            ['title' => 'HCV', 'description' => 'Hepatitis C virus – Вирус гепатита С'],
//            ['title' => 'HIV', 'description' => 'Human immunodeficiency virus – Вирус иммунодефицита человека'],
//            ['title' => 'HHV', 'description' => 'Human herpes virus – Вирус герпеса человека'],
//            ['title' => 'HLA', 'description' => 'Human leukocyte antigen – Человеческий лейкоцитарный антиген'],
//            ['title' => 'HPV', 'description' => 'Human papilloma virus – Вирус папилломы человека'],
//            ['title' => 'HSV', 'description' => 'Herpes simplex virus – Вирус простого герпеса'],
//            ['title' => 'Ig', 'description' => 'Immunoglobulin – Иммуноглобулин'],
//            ['title' => 'IL', 'description' => 'Interleukin – Интерлейкин'],
//            ['title' => 'LC', 'description' => 'Langerhans cell – Клетка Лангерганса'],
//            ['title' => 'LN', 'description' => 'Lymph node – Лимфатический узел'],
//            ['title' => 'MALT', 'description' => 'Mucose-associated lymphoid tissues – Лимфоидная ткань слизистых оболочек'],
//            ['title' => 'MC', 'description' => 'Mast cell – Тучная клетка'],
//            ['title' => 'MHC', 'description' => 'Major histocompatibility complex – Главный комплекс гистосовместимости'],
//            ['title' => 'NK', 'description' => 'Natural killer – Естественный киллер'],
//            ['title' => 'PCR', 'description' => 'Polymerase chain reaction – Полимеразная цепная реакция'],
//            ['title' => 'TxA2', 'description' => 'Tromboxan – Тромбоксан'],
//        ];
//
//        foreach ($abbreviations as $abbr) {
//            Abbreviation::create($abbr);
//        }
//    }

}
