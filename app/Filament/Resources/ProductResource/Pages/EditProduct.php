<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProduct extends EditRecord
{
    protected static string $resource = ProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
    protected function mutateFormDataBeforeFill(array $data): array {
        array_walk($data['attributes'], function (&$item) {
            $item = json_encode($item);
        });
        return $data;
    }
    protected function mutateFormDataBeforeSave(array $data): array {
        array_walk($data['attributes'], fn(&$item) => $item = json_decode($item, true));
        return $data;
    }
}
