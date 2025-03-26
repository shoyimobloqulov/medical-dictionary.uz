<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AbbreviationResource\Pages;
use App\Filament\Resources\AbbreviationResource\RelationManagers;
use App\Models\Abbreviation;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AbbreviationResource extends Resource
{
    protected static ?string $navigationIcon = 'heroicon-o-folder';
    protected static ?string $navigationLabel = 'Специальных Терминов';
    protected static ?string $pluralLabel = 'Специальных Терминов';
    protected static ?string $navigationGroup = 'Медицинский словарь';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->label('Название')
                    ->required()
                    ->maxLength(255),

                Forms\Components\Textarea::make('description')
                    ->label('Описание')
                    ->rows(5)
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Название')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('description')
                    ->label('Описание')
                    ->limit(50) // Matnni 50 belgigacha qisqartirish
                    ->tooltip(fn($record) => $record->description),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAbbreviations::route('/'),
            'create' => Pages\CreateAbbreviation::route('/create'),
            'edit' => Pages\EditAbbreviation::route('/{record}/edit'),
        ];
    }
}
