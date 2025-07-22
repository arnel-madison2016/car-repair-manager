<?php

namespace App\Filament\Resources\RepairSheetResource\Pages;

use App\Filament\Resources\RepairSheetResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Mews\Purifier\Facades\Purifier;

class EditRepairSheet extends EditRecord
{
    protected static string $resource = RepairSheetResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array {

        $data['probleme_reporte'] = Purifier::clean($data['probleme_reporte'], 'filament-safe');
        
        return $data;
    }
}
