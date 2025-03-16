<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicalTermTranslation extends Model
{
    use HasFactory;

    protected $fillable = ['medical_term_id', 'language_id', 'name', 'description'];

    public function medicalTerm()
    {
        return $this->belongsTo(MedicalTerm::class);
    }

    public function language()
    {
        return $this->belongsTo(Language::class);
    }
}
