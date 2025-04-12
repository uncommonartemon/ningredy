<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Forms;
use App\Models\Settings;
use App\Models\Product;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Pages\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Select;

class EditSettings extends Page implements HasForms
{
    use Forms\Concerns\InteractsWithForms;

    public ?Settings $appSettings; 
    public $styles = [];
    public $settings = [];
    protected static ?string $navigationIcon = 'heroicon-o-cog';
    protected static string $view = 'filament.pages.edit-settings';
    

    public function mount(): void
    {
        $this->appSettings = Settings::first();
        $data = $this->appSettings ? $this->appSettings->toArray() : [];
        if (!$this->appSettings) {
            $data += [
                'styles' => ['dark_1'=> '#16161B','dark_2'=> '#1D1D25','dark_3'=> '','dark_4'=> '#303046','contrast_1' => '#80ffdb','contrast_2' => '#1FAF94','black'=> '#1F1F1F','white'=> '#D9E9CF',],
            ];
            $data += [
                'settings' => [
                    'default_theme' => 'dark', 'currency' => 'Kč', 'main_language' => 'en',
                    'show'=> ['home_slider' => true, 'show_case' => true, 'best_discounts'=> true, 'new_releases' => true, 'tags' => true],
                ]
            ];
            $this->appSettings = Settings::create($data);
        } else {$data = $this->appSettings->toArray();}
        $this->form->fill($data);
    }
    protected function getFormSchema(): array
    {
        return [
            Forms\Components\Section::make()->schema([
                ToggleButtons::make('settings.default_theme')->options([
                    'dark' => 'dark',
                    'light' => 'light'
                ])->inline()->default('dark'),
            ])->columns(5),
            Forms\Components\Section::make('Show')->schema([
                Toggle::make('settings.show.home_slider')->default(true),
                Toggle::make('settings.show.show_case')->default(true),
                Toggle::make('settings.show.best_discounts')->default(true),
                Toggle::make('settings.show.new_releases')->default(true),
                Toggle::make('settings.show.tags')->default(true),
            ])->columns(5),
            Forms\Components\Section::make()->schema([
                Select::make('styles.showcase')
                ->multiple()
                
                ->searchable()
                ->label('Products in showcase')
                ->options( Product::all()->mapWithKeys(function ($product) {
                    return [
                        $product->id => $product->id . ' - ' . data_get($product, 'title.en'),
                    ];
                })),
            ]),
            Forms\Components\Section::make('Country/Language')->schema([
                Forms\Components\TextInput::make('settings.currency')->default('Kč'),
                Forms\Components\Select::make('settings.main_language')
                ->options([
                    'en' => 'english', 'cz' => 'czech', 'ru' => 'russian'
                ])->default('en'),
            ])->columns(5),
            Forms\Components\Section::make('Colors')->schema([
                Forms\Components\Section::make('Dark theme')->schema([
                    ColorPicker::make('styles.dark_1')->default('#16161B'),
                    ColorPicker::make('styles.dark_2')->default('#1D1D25'),
                    ColorPicker::make('styles.dark_3')->default(''),
                    ColorPicker::make('styles.dark_4')->default('#303046'),
                    ColorPicker::make('styles.contrast_1')->default('#80ffdb'),
                    ColorPicker::make('styles.contrast_2')->default('#1FAF94'),
                    ColorPicker::make('styles.contrast_3')->default(''),
                    ColorPicker::make('styles.contrast_4')->default(''),
                ])->columns(4),
                Forms\Components\Section::make('Light theme')->schema([
                    ColorPicker::make('styles.light_1'),
                    ColorPicker::make('styles.light_2'),
                    ColorPicker::make('styles.light_3'),
                    ColorPicker::make('styles.light_4'),
                    ColorPicker::make('styles.light_contrast_color_1'),
                    ColorPicker::make('styles.light_contrast_color_2'),
                    ColorPicker::make('styles.light_contrast_color_3'),
                    ColorPicker::make('styles.light_contrast_color_4'),
                ])->columns(4),
                Forms\Components\Section::make('Static colors')->schema([
                    ColorPicker::make('styles.black')->default('#1F1F1F'),
                    ColorPicker::make('styles.black2'),
                    ColorPicker::make('styles.white')->default('#D9E9CF'),
                    ColorPicker::make('styles.white2'),
                ])->columns(4),
            ])->columns(4),
            
        ];
    }

    // Метод для сохранения изменений
    public function save(): void
    {
        $data = $this->form->getState();
        if ($this->appSettings->exists) {
            if(isset($data['settings']['loading_dark'])) {
                
            }
            $this->appSettings->update($data);
        } else {
            Settings::create($data);
        }

        Notification::make()
        ->title('Settings updated')
        ->success()
        ->send();
    }

    protected function getActions(): array
    {
        return [
            Action::make('save')
                ->label('Сохранить')
                ->action('save')
                ->button(),
        ];
    }
}