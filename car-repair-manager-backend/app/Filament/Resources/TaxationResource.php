<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TaxationResource\Pages;
use App\Filament\Resources\TaxationResource\RelationManagers;
use App\Models\Taxation;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\Relationship;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TaxationResource extends Resource
{
    protected static ?string $model = Taxation::class;

    protected static ?string $navigationIcon = 'heroicon-o-currency-euro';

    protected static ?string $navigationGroup = 'Services Management';
    public static function getNavigationBadge(): ?string {

        return static::getModel()::count();        
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                
                Select::make('service_id')
                    ->relationship('service', 'name')
                    ->searchable()
                    ->preload(),

                TextInput::make('cout_prestation')
                    ->label('Cout forfaitaire')
                    ->numeric()
                    ->rule('regex:/^\d{1,7}(\.\d{1,2})?$/') // 7 chiffres max avant la virgule + 2 décimales max
                    ->step(0.01) // autorise les centimes
                    ->prefix('XFA')
                    ->mask('9999999.99')
                    ->placeholder('Ex : 1234567.89')
                    ->required(),

                TextInput::make('reduction')
                    ->label('Remise')
                    ->numeric()
                    ->rule('regex:/^\d{1,2}(\.\d{1,2})?$/') // 2 chiffres max avant la virgule + 2 décimales max
                    ->step(0.01) // autorise les centimes
                    ->prefix('%')
                    ->mask('99.99')
                    ->placeholder('Ex : 67.89'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                
                TextColumn::make('service.name')
                    ->label('Prestations')
                    ->weight('bold')
                    ->formatStateUsing(fn($state) => ucfirst($state))
                    ->searchable()
                    ->sortable(true),

                    TextColumn::make('cout_prestation')
                    ->label('Cout forfaitaire')
                    ->money('XFA'),

                TextColumn::make('reduction')                        
                    ->label('Remise')                    
                    ->weight('bold')
                    ->alignCenter()
                    ->formatStateUsing(fn ($state) => number_format($state * 1, 2) . '%'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTaxations::route('/'),
            'create' => Pages\CreateTaxation::route('/create'),
            'edit' => Pages\EditTaxation::route('/{record}/edit'),
        ];
    }
}
