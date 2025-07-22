<?php

namespace App\Filament\Resources\RepairSheetResource\Pages;

use App\Filament\Resources\RepairSheetResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Mews\Purifier\Facades\Purifier;

class CreateRepairSheet extends CreateRecord
{
    protected static string $resource = RepairSheetResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array {

        $data['probleme_reporte'] = Purifier::clean($data['probleme_reporte'] ?? '', 'filament-safe');
        
        return $data;
    }
}
