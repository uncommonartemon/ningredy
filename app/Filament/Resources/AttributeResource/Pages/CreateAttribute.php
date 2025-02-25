<?php

namespace App\Filament\Resources\AttributeResource\Pages;

use App\Filament\Resources\AttributeResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use App\Models\Attribute;
use Filament\Notifications\Notification;

class CreateAttribute extends CreateRecord
{
    protected static string $resource = AttributeResource::class;

    public function beforeSave()
    {
       
    }
    protected function beforeCreate(): void
    {
        $data = $this->form->getState(); 
        
        if (!$data['is_creating']) {
            $data['key'] = $data['select_key'];
            $attribute = Attribute::where('key', $data['key'])->first();
            if ($attribute) {
                $data['translate'] = $attribute->translate;
            }
        }
        // Проверяем, существует ли атрибут с таким key
        $attribute = Attribute::where('key', $data['key'])->first();

        if ($attribute) {
            // Если существует, обновляем запись
            $attribute->update([
                'translate' => $data['translate'] ?? $attribute->translate,
                'values' => $data['values'] ?? $attribute->values,
            ]);
            Notification::make()
                ->title('Атрибут обновлен')
                ->success()
                ->send();

            $this->halt();
        }
    }
    protected function mutateFormDataBeforeCreate(array $data): array
    {
    
        if ($data['is_creating']) {
        }
        return $data;  
    }
}
