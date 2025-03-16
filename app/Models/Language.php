<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'code', 'flag']; // Flag qo‘shildi

    public function translations()
    {
        return $this->hasMany(MedicalTermTranslation::class);
    }
}
