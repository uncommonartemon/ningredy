<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Forms;
use App\Models\Settings;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Pages\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\ToggleButtons;

class EditSettings extends Page implements HasForms
{
    use Forms\Concerns\InteractsWithForms;

    public Settings $settings;
    public $styles = [];
    protected static ?string $navigationIcon = 'heroicon-o-cog';
    protected static string $view = 'filament.pages.edit-settings';
    

    public function mount(): void
    {
        $this->settings = Settings::first() ?? Settings::create();
        $this->form->fill($this->settings->toArray());
        
    }

    // Определяем схему формы
    protected function getFormSchema(): array
    {
        return [
            Forms\Components\Section::make()->schema([
                ToggleButtons::make('styles.default_theme')->options([
                    'dark' => 'dark',
                    'light' => 'light'
                ])->inline(),
            ]),
            Forms\Components\Section::make('Colors')->schema([
                Forms\Components\Section::make('Dark theme')->schema([
                    ColorPicker::make('styles.dark_1'),
                    ColorPicker::make('styles.dark_2'),
                    ColorPicker::make('styles.dark_3'),
                    ColorPicker::make('styles.dark_4'),
                    ColorPicker::make('styles.contrast_1'),
                    ColorPicker::make('styles.contrast_2'),
                    ColorPicker::make('styles.contrast_3'),
                    ColorPicker::make('styles.contrast_4'),
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
                    ColorPicker::make('styles.black'),
                    ColorPicker::make('styles.black2'),
                    ColorPicker::make('styles.white'),
                    ColorPicker::make('styles.white2'),
                ])->columns(4),
            ])->columns(4),
            // Forms\Components\Section::make('Sizes')->schema([
            //     // Forms\Components\TextInput::make('')
            // ])->columns(4)
        ];
    }

    // Метод для сохранения изменений
    public function save(): void
    {
        // Получаем актуальное состояние формы
        $data = $this->form->getState();
        // Обновляем запись
        $this->settings->update($data);
        // Выводим уведомление об успехе
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