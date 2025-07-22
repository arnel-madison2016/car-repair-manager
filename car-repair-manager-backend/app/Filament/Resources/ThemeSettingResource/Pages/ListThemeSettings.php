<?php

namespace App\Filament\Resources\ThemeSettingResource\Pages;

use App\Filament\Resources\ThemeSettingResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListThemeSettings extends ListRecords
{
    protected static string $resource = ThemeSettingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
