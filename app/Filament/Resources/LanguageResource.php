<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LanguageResource\Pages;
use App\Models\Language;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class LanguageResource extends Resource
{
    protected static ?string $model = Language::class;
    protected static ?string $navigationIcon = 'heroicon-o-flag'; // Bayroq belgisi
    protected static ?string $navigationGroup = 'Медицинский словарь';
    protected static ?string $label = "Язык";

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label('Название языка')
                    ->required(),

                TextInput::make('code')
                    ->label('Код')
                    ->required(),

                TextInput::make('flag')
                    ->label('Флаг (ссылка на изображение)') // "Bayroq (rasm manzili)"
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->label('ID')->sortable(),
                TextColumn::make('name')->label('Название')->sortable(),
                TextColumn::make('code')->label('Код')->sortable(),
                Tables\Columns\ImageColumn::make('flag') // Flag ustuni rasmlar uchun
                ->label('Флаг')
                    ->circular(),
            ])
            ->searchable()
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make()->label('Редактировать'), // "Tahrirlash"
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make()->label('Удалить выбранные'), // "Tanlanganlarni o‘chirish"
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLanguages::route('/'),
            'create' => Pages\CreateLanguage::route('/create'),
            'edit' => Pages\EditLanguage::route('/{record}/edit'),
        ];
    }
}
