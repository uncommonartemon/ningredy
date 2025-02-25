<?php

namespace App\Filament\Resources\AttributeResource\Pages;

use App\Filament\Resources\AttributeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAttribute extends EditRecord
{
    protected static string $resource = AttributeResource::class;

    // Хуки для страниц
    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    public function getTitle(): string
    {
        return '';
    }

    public function getFormSchema(): array
    {
        return [];
    }

    public function getInitialFormData(): array
    {
        return [];
    }

    public function mutateFormDataBeforeFill(array $data): array
    {
        $data['select_key'] = $data['key'] ?? null; // Синхронизация ключа
        $data['edit'] = true;
        return $data;
    }

    public function mutateFormDataBeforeSave(array $data): array
    {
        return $data;
    }

    public function afterSave()
    {
        
    }

    public function afterDelete()
    {
        // Действия после удаления записи
    }

    public function afterRestore()
    {
        // Действия после восстановления записи
    }

    public function beforeSave()
    {
        //dd($this->form->getState());
    }

    public function beforeDelete()
    {
        // Действия до удаления записи
    }

    public function beforeRestore()
    {
        // Действия до восстановления записи
    }
}