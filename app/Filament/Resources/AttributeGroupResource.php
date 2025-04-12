<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AttributeGroupResource\Pages;
use App\Filament\Resources\AttributeGroupResource\RelationManagers;
use App\Models\AttributeGroup;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AttributeGroupResource extends Resource
{
    protected static ?string $model = AttributeGroup::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->columns(3)
                    ->schema([
                        Forms\Components\TextInput::make('title')->label('title en'),
                        Forms\Components\TextInput::make('translate.cz')->label('title cz'),
                        Forms\Components\TextInput::make('translate.ru')->label('title ru'),
                    ]),
                Forms\Components\FileUpload::make('icon')->label('icon for dark theme (64px optimal)')
                    ->image()->disk('public')->directory('attribute_group'),
                Forms\Components\FileUpload::make('icon_light')->label('icon for light theme (64px optimal)')
                    ->image()->disk('public')->directory('attribute_group')
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->label('ID')->sortable(),
                Tables\Columns\TextColumn::make('title')->searchable(),
                Tables\Columns\TextColumn::make('translate')->searchable(),
                Tables\Columns\ImageColumn::make('icon'),
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
            'index' => Pages\ListAttributeGroups::route('/'),
            'create' => Pages\CreateAttributeGroup::route('/create'),
            'edit' => Pages\EditAttributeGroup::route('/{record}/edit'),
        ];
    }
}
