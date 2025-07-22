<?php

namespace App\Filament\Widgets;

use App\Models\RepairSheet;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class RepairSheetStatusTable extends BaseWidget {
    protected int|string|array $columnSpan = 'full';

    protected function getTableQuery(): Builder {

        return RepairSheet::with('vehicule')->latest();
    }

    protected function getTableColumns(): array {

        return [
            TextColumn::make('vehicule.display_name')
                ->label('Véhicule'),

            TextColumn::make('status')
                ->badge()
                ->color(fn ($state) => match ($state) {
                    'pending' => 'warning',
                    'in_process' => 'info',
                    'delivered' => 'success',
                    default => 'gray'
                }),

            TextColumn::make('created_at')->dateTime('d/m/Y H:i'),
        ];
    }

    //public function table(Table $table): Table {

        //return $table
        //    ->query(function (Builder $query) {
        //        return $query->with('vehicule')->latest();
        //    })
        //    ->columns([
        //        TextColumn::make('vehicule.display_name')->label('Véhicule'),
        //        TextColumn::make('status')->badge(),
        //        TextColumn::make('created_at')->dateTime(),
        //    ]);
    //}
}
