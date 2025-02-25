<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CategoryResource\Pages;
use App\Filament\Resources\CategoryResource\RelationManagers;
use App\Models\Category;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use Filament\Forms\Set;

use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

use Illuminate\Database\Eloquent\SoftDeletingScope;


class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
        ->schema([
            Forms\Components\Section::make('Name/Название')
                ->collapsible()
                ->columns(3)
                ->schema([
                    Forms\Components\TextInput::make('name.en')  // Для английского
                        ->label('Название (English)')
                        ->required(),
                    Forms\Components\TextInput::make('name.ru')  // Для русского
                        ->label('Название (Russian)')
                        ->required(),
                    Forms\Components\TextInput::make('name.cz')  // Для чешского
                        ->label('Название (Czech)')
                        ->required(),
                ]),
            Forms\Components\Section::make('Category Image')
                ->collapsible()
                ->schema([
                    Forms\Components\FileUpload::make('image') // Поле для загрузки изображения
                        ->label('Category Image')
                        ->disk('public')
                        ->image() // Ожидаем изображение
                        ->directory('categories')
            
                ]),
                Forms\Components\Checkbox::make('is_active')
                ->label('Показать категорию?')
                ->default(true)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name.en') // Отображаем поле name на английском
                    ->label('Name (English)')
                    ->sortable(),
                Tables\Columns\TextColumn::make('name.ru') // Отображаем поле name на русском
                    ->label('Name (Russian)')
                    ->sortable(),
                Tables\Columns\TextColumn::make('name.cz') // Отображаем поле name на чешском
                    ->label('Name (Czech)')
                    ->sortable(),
                Tables\Columns\ImageColumn::make('image') // Столбец для изображения
                    ->label('Image')
                    ->sortable(),
            ])
            ->filters([
                // Ваши фильтры
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
            'index' => Pages\ListCategories::route('/'),
            'create' => Pages\CreateCategory::route('/create'),
            'edit' => Pages\EditCategory::route('/{record}/edit'),
        ];
    }
}
