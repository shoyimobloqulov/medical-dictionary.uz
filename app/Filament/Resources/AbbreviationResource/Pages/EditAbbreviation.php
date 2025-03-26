<?php

namespace App\Filament\Resources\AbbreviationResource\Pages;

use App\Filament\Resources\AbbreviationResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAbbreviation extends EditRecord
{
    protected static string $resource = AbbreviationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
