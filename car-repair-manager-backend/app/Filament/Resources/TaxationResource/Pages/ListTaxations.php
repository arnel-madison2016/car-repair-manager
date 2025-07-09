<?php

namespace App\Filament\Resources\TaxationResource\Pages;

use App\Filament\Resources\TaxationResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTaxations extends ListRecords
{
    protected static string $resource = TaxationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
