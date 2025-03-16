<?php

namespace App\Filament\Resources\MedicalTermResource\Pages;

use App\Filament\Resources\MedicalTermResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMedicalTerm extends EditRecord
{
    protected static string $resource = MedicalTermResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
