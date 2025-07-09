<?php

namespace App\Filament\Resources\ParameterResource\Pages;

use App\Filament\Resources\ParameterResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListParameters extends ListRecords
{
    protected static string $resource = ParameterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
