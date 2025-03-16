<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicalTerm extends Model
{
    use HasFactory;

    protected $fillable = [];

    public function translations()
    {
        return $this->hasMany(MedicalTermTranslation::class);
    }
}
