<?php

namespace App\Filament\Resources\PrestationResource\Pages;

use App\Filament\Resources\PrestationResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPrestations extends ListRecords
{
    protected static string $resource = PrestationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
