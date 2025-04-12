<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SliderResource\Pages;
use App\Filament\Resources\SliderResource\RelationManagers;
use App\Models\Slider;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;

class SliderResource extends Resource
{
    protected static ?string $model = Slider::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()->schema([
                    Forms\Components\TextArea::make('text.main.en')->label('main text en'),
                    Forms\Components\TextArea::make('text.main.cz')->label('main text cz'),
                    Forms\Components\TextArea::make('text.main.ru')->label('main text ru')
                ])->columns(3),
                Forms\Components\Section::make()->schema([
                    Forms\Components\TextArea::make('text.additional.en')->label('additional text en'),
                    Forms\Components\TextArea::make('text.additional.cz')->label('additional text cz'),
                    Forms\Components\TextArea::make('text.additional.ru')->label('additional text ru'),
                ])->columns(3),
                Forms\Components\Section::make()->schema([
                    TextInput::make('link'),
                    TextInput::make('order')->columnSpan(1)->integer(),
                ])->columns(2),
                FileUpload::make('image')->disk('public')->directory('slider')->columnSpan('full')->image(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                ->label('ID')
                ->sortable(),
                Tables\Columns\TextColumn::make('text.main.en')->label('text'),
                Tables\Columns\TextColumn::make('text.additional.en')->label('additional'),
                Tables\Columns\TextColumn::make('order')->label('order')->sortable(),
                Tables\Columns\TextColumn::make('link'),
                Tables\Columns\ImageColumn::make('image')
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
            'index' => Pages\ListSliders::route('/'),
            'create' => Pages\CreateSlider::route('/create'),
            'edit' => Pages\EditSlider::route('/{record}/edit'),
        ];
    }
}
