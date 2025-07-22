<?php

namespace App\Filament\Resources\RepairSheetResource\Pages;

use App\Filament\Resources\RepairSheetResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRepairSheets extends ListRecords
{
    protected static string $resource = RepairSheetResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
