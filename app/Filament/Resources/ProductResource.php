<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\Product;
use App\Models\Attribute;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Models\Category;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;


class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()->compact()
                ->columns(3)
                ->schema([
                    Forms\Components\TextInput::make('title.en')->label('Name')
                    ->required(),
                    Forms\Components\TextInput::make('title.cz')->label('Jméno'),
                    Forms\Components\TextInput::make('title.ru')->label('Название')
                ]),
                Forms\Components\Section::make()->compact()
                ->columns(3)
                ->schema([
                    Forms\Components\TextInput::make('brand'),
                    Forms\Components\TextInput::make('type'),
                    Forms\Components\TextInput::make('country')
                ]),
                Forms\Components\Section::make()->compact()
                ->columns(2)
                ->columnSpan(1) 
                ->schema([
                    Forms\Components\TextInput::make('price')
                    ->integer()
                    ->required(),
                    Forms\Components\TextInput::make('discount')->label('price with discount')
                    ->integer(),
                ]),
                Forms\Components\Section::make()->columnSpan(1)->compact()
                ->schema([
                    Forms\Components\Select::make('category_id')
                        ->label('Category') 
                        ->options(Category::all()->pluck('name.en', 'id')->toArray())
                        ->searchable() 
                        ->required(), 
                ]),
                Forms\Components\Section::make()->compact()
                ->columns(3)
                ->schema([
                    Textarea::make('description.en')->label('Description'),
                    Textarea::make('description.cz')->label('Popis'),
                    Textarea::make('description.ru')->label('Описание'),
                ]),

                Forms\Components\Section::make()
                ->columns(1)
                ->schema([
                    Forms\Components\Select::make('attributes')
                    ->required()
                    ->reactive()
                    ->multiple()
                    ->options(
                        function () {
                            $options = [];
                            foreach (Attribute::all() as $attribute) {
                                foreach ($attribute->values as $value) {
                                    $options[json_encode([$attribute->key => $value])] = json_encode([$attribute->key => $value]);
                                }
                            };
                            return $options;
                        }
                    ), 
                ]),

                Forms\Components\Section::make()
                ->schema([
                    FileUpload::make('images')
                    ->multiple()
                    ->reorderable()
                    ->downloadable()
                    ->disk('public')
                    ->image() 
                    ->directory('products')
                ]),
                Forms\Components\Section::make()->schema([
                    Forms\Components\TagsInput::make('tags')
                    ->label('Tags')
                    
                ])->columnSpan(1)->compact(),
                Forms\Components\Section::make()->schema([
                    Forms\Components\Toggle::make('used')->default(false)->label('used?'),
                    Forms\Components\Toggle::make('active')->default(true)->label('active?'),
                ])->columns(2)->columnSpan(1)->compact(),

                Forms\Components\Section::make('Additional')->schema([
                    
                ])->columns(2)->compact(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // Отображение ID продукта
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),
                    
                // Отображение названия продукта (английская версия)
                Tables\Columns\TextColumn::make('title.en')
                    ->label('Name')
                    ->sortable()
                    ->searchable(),
                    
                // Отображение бренда
                Tables\Columns\TextColumn::make('brand')
                    ->sortable()
                    ->searchable(),
                    
                Tables\Columns\TextColumn::make('type')
                    ->sortable()
                    ->searchable(),
                
                // Отображение категории (если у продукта есть связь с категорией)
                Tables\Columns\TextColumn::make('category.name.en')
                ->label('Category')
                ->sortable()
                ->searchable(query: function (Builder $query, string $search) {
                    // Ищем по связанному объекту category
                    return $query->orWhereHas('category', function (Builder $query) use ($search) {
                        // Если в базе название хранится как JSON, например, {"en": "Название", "cz": "Jméno", ...}
                        // можно использовать синтаксис доступа к JSON: name->en (MySQL 5.7+ поддерживает JSON_EXTRACT)
                        $query->where('name->en', 'like', "%{$search}%");
                    });
                }),
                    
                // Отображение цены
                Tables\Columns\TextColumn::make('price')
                    ->label('Price')
                    ->sortable()
                    ->formatStateUsing(fn ($state) => '$' . number_format($state, 2)),
                
                Tables\Columns\TextColumn::make('discount')
                    ->label('Discount')
                    ->sortable()
                    ->formatStateUsing(fn ($state) => '$' . number_format($state, 2)),
                // Отображение даты создания продукта
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
                Tables\Columns\ImageColumn::make('images')

            ])
            ->filters([
                // Можно добавить фильтры по категориям, брендам и другим полям
                Tables\Filters\Filter::make('active')
                    ->query(fn (Builder $query): Builder => $query->where('active', true))
                    ->label('Active Products'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                // Дополнительные действия, например просмотр записи
                Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
