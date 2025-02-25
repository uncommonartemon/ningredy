<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AttributeResource\Pages;
use App\Filament\Resources\AttributeResource\RelationManagers;
use App\Models\Attribute;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;
use Filament\Forms\Set;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Condition;
use Illuminate\Support\Facades\Log;

class AttributeResource extends Resource
{
    protected static ?string $model = Attribute::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
{
    return $form
        ->schema([ 
            Toggle::make('is_creating')
                ->label('Создать ключ атрибута')
                ->default(false)
                ->visible(fn ($get) => !$get('edit'))
                ->reactive(),

            // Раздел для создания ключа
            Forms\Components\Section::make('Create key attribute')
                ->columns(3)
                ->visible(fn ($get) => $get('is_creating') === true || $get('edit'))
                ->schema([
                    Forms\Components\TextInput::make('key')
                    ->placeholder('key eng')->required(fn ($get) => $get('is_creating'))
                    ->label('En')
                    ->live()
                    ->afterStateUpdated(function ($state, callable $set, $get) {
                        if ($state && !$get('edit')) {
                            $attribute = Attribute::where('key', $state)->first();
                            if ($attribute) {
                                $values = $attribute->values;
                                $set('values', $values);
                                $set('select_key', $attribute->key);
                                $set('translate.cz', $attribute->translate['cz']);
                                $set('translate.ru', $attribute->translate['ru']);
                            } else {
                                $set('values', []);
                            }
                        }
                    }),
                    Forms\Components\TextInput::make('translate.cz')->required(fn ($get) => $get('is_creating'))
                    ->placeholder('key cz'),
                    Forms\Components\TextInput::make('translate.ru')->required(fn ($get) => $get('is_creating'))
                    ->placeholder('key ru'),  
                ]),

            Forms\Components\Section::make('Select key attribute')
            ->visible(fn ($get) => $get('is_creating') === false && !$get('edit'))
            ->schema([
                Select::make('select_key')
                    ->options(
                        Attribute::all()->pluck('key', 'key')->toArray()
                    )
                    ->searchable()
                    ->live()
                    ->required(fn ($get) => !$get('is_creating'))
                    //->default(fn ($get) => $get('key')) 
                    ->placeholder('Выберите ключ')
                    ->afterStateUpdated(function ($state, callable $set,$get) {
                        if ($state && !$get('edit')) {
                            $attribute = Attribute::where('key', $state)->first();
                            if ($attribute) {
                                $values = $attribute->values;
                                $set('values', $values);
                                $set('key', $attribute->key);
                                $set('translate.cz', $attribute->translate['cz']);
                                $set('translate.ru', $attribute->translate['ru']);
                            } else {
                                $set('values', []);
                            }
                        }
                    }),
            ]),


            Forms\Components\TagsInput::make('values')
            ->label('Значения')
            ->placeholder('Введите значение')
        ]);
}

public static function table(Table $table): Table
{
    return $table
        ->columns([
            // Колонка ID
            Tables\Columns\TextColumn::make('id')
                ->label('ID')
                ->sortable()
                ->searchable(),

            // Колонка Keys
            Tables\Columns\TextColumn::make('key')
            ->label('en')
            ->formatStateUsing(fn($state) => $state ?? '-') // Если ключ отсутствует, показываем '-'
            ->searchable(),

            // Колонка для ключей RU
            Tables\Columns\TextColumn::make('translate.cz')
            ->label('cz')
            ->formatStateUsing(fn($state) => $state ?? '-')
            ->searchable(),

            // Колонка для ключей CZ
            Tables\Columns\TextColumn::make('translate.ru')
            ->label('ru')
            ->formatStateUsing(fn($state) => $state ?? '-')
            ->searchable(),

            // Колонка Values
            Tables\Columns\TextColumn::make('values')
                ->label('Values')
                ->formatStateUsing(function ($state) {
                    if (is_array($state)) {
                        return implode(', ', $state);
                    }
                    return $state;
                })
                ->extraAttributes(['style' => 'width: 50%;']) // Задаём большую ширину для этой колонки
                ->sortable()
                ->searchable(),
        ])
        ->filters([
            // Добавить фильтры, если необходимо
        ])
        ->actions([
            Tables\Actions\EditAction::make(), // Действие редактирования
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
            'index' => Pages\ListAttributes::route('/'),
            'create' => Pages\CreateAttribute::route('/create'),
            'edit' => Pages\EditAttribute::route('/{record}/edit'),
        ];
    }
}
