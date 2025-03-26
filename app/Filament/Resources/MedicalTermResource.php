<?php

namespace App\Filament\Resources;

use App\Exports\MedicalTermsExport;
use App\Filament\Resources\MedicalTermResource\Pages;
use App\Imports\MedicalTermsImport;
use App\Models\Language;
use App\Models\MedicalTerm;
use Filament\Forms;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TagsColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Maatwebsite\Excel\Facades\Excel;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
use Stevebauman\Purify\Facades\Purify;

class MedicalTermResource extends Resource
{
    protected static ?string $model = MedicalTerm::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Медицинский словарь';
    protected static ?string $label = "Медицинские термины";

    public static function form(Form $form): Form
    {
//        Comment
        return $form
            ->schema([
                Repeater::make('translations')
                    ->label("Переводы")
                    ->relationship()
                    ->schema([
                        Select::make('language_id')
                            ->label('Язык')
                            ->options(Language::all()->pluck('name', 'id'))
                            ->searchable()
                            ->allowHtml()
                            ->preload()
                            ->createOptionUsing(fn($data) => Language::create(['name' => $data['name'], 'code' => $data['code'], 'flag' => $data['flag']]))
                            ->createOptionForm([
                                TextInput::make('name')
                                    ->label('Название')
                                    ->required(),
                                TextInput::make('code')
                                    ->label('Код')
                                    ->required(),
                                TextInput::make('flag')
                                    ->label('Флаг')
                                    ->required(),
                            ])
                            ->getOptionLabelUsing(function ($value): string {
                                $language = Language::find($value);
                                return static::getCleanOptionString($language);
                            })
                            ->required(),

                        TextInput::make('name')
                            ->label('Медицинский термин')
                            ->required()
                            ->columnSpanFull()
                            ->maxLength(255),

                        RichEditor::make('description')
                            ->label('Описание')
                            ->required()
                            ->columnSpan(2),
                    ])
                    ->grid(2)
                    ->columnSpanFull()
                    ->required(),
            ]);
    }

    public static function getCleanOptionString(Model $model): string
    {
        return Purify::clean(
            view('components.flag-option')
                ->with('name', $model?->name)
                ->with('code', $model?->code)
                ->with('image', $model?->flag)
                ->render()
        );
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->label('ID')->sortable(),
                TagsColumn::make('translations_list')
                    ->label('Переводы')
                    ->getStateUsing(fn($record) => $record->translations->pluck('name')->toArray()),

                TagsColumn::make('translations.language.name')
                    ->label('Язык')
                    ->getStateUsing(fn($record) => $record->translations->pluck('language.name')->filter()->toArray()),


                TextColumn::make('created_at')->label('Дата создания')->dateTime(),
            ])
            ->searchable()
            ->filters([
                SelectFilter::make('language')
                    ->label('Фильтр по языку')
                    ->options(Language::pluck('name', 'id'))
                    ->searchable()
                    ->query(function ($query, $data) {
                        if (empty($data['value'])) return $query;
                        return $query->whereHas('translations', function ($q) use ($data) {
                            $q->where('language_id', $data['value']);
                        });
                    }),
            ])
            ->actions([
            ])
            ->headerActions([
                Tables\Actions\Action::make('export')
                    ->label('Экспорт в Excel')
                    ->icon('heroicon-o-document-arrow-down')
                    ->action(fn() => Excel::download(new MedicalTermsExport, 'medical_terms.xlsx')),
            ])
            ->bulkActions([

                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMedicalTerms::route('/'),
            'create' => Pages\CreateMedicalTerm::route('/create'),
            'edit' => Pages\EditMedicalTerm::route('/{record}/edit'),
        ];
    }
}
