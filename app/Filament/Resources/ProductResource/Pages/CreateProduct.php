<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateProduct extends CreateRecord
{
    protected static string $resource = ProductResource::class;

    protected function beforeFill(): void
    {
        //dd($this->form);

    }
    protected function mutateFormDataBeforeCreate(array $data): array {
        
        array_walk($data['attributes'], fn(&$item) => $item = json_decode($item, true));
        return $data;
    }
}

