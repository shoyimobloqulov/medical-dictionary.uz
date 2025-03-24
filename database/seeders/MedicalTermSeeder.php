<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;

class MedicalTermSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        $languages = DB::table('languages')->pluck('id'); // mavjud tillarni olamiz
        $alphabet = range('A', 'Z'); // Harflar diapazoni

        foreach ($alphabet as $letter) {
            for ($i = 0; $i < 100; $i++) {
                $medicalTermId = DB::table('medical_terms')->insertGetId([
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                foreach ($languages as $languageId) {
                    DB::table('medical_term_translations')->insert([
                        'medical_term_id' => $medicalTermId,
                        'language_id' => $languageId,
                        'name' => $letter . $faker->word,
                        'description' => $faker->sentence,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }
    }
}
