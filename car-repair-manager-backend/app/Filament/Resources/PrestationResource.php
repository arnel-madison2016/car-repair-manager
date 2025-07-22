<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PrestationResource\Pages;
use App\Filament\Resources\PrestationResource\RelationManagers;

use App\Models\Service;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\FontSize;
use Filament\Tables;
use Filament\Tables\Table;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\TextColumn\TextColumnSize;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PrestationResource extends Resource
{
    protected static ?string $model = Service::class;

    protected static ?string $navigationIcon = 'heroicon-o-wrench-screwdriver';
    protected static ?string $navigationGroup = 'Services Management';
    public static function getNavigationBadge(): ?string {

        return static::getModel()::count();        
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                
                TextInput::make('name')
                    ->label('Désignation')
                    ->required()
                    ->maxLength(255),

                Select::make('category_service_id')
                    ->relationship('category', 'name')
                    ->searchable()
                    ->preload()
                    ->createOptionForm([

                        TextInput::make('name')
                            ->label('Nom')
                            ->required()
                            ->maxLength(150)
                            ->required(),

                        TextInput::make('cout_forfaitaire')
                            ->label('Cout forfaitaire')
                            ->required()
                            ->numeric()
                            ->inputMode('decimal'),

                        Textarea::make('details')
                            ->label('Détails')
                            ->rows(5)
                            ->cols(20)
                            ->required(),
                    ]),

                Textarea::make('details')
                    ->label('Détails')
                    ->rows(5)
                    ->required()
                    ->columnSpanFull(),                
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                
                TextColumn::make('name')
                    ->label('Désignation')
                    ->weight('bold')
                    ->formatStateUsing(fn($state) => ucfirst($state))
                    ->searchable(),

                TextColumn::make('category.name')                        
                    ->label('categorie'),

                TextColumn::make('details')
                    ->label('Details')
                    ->size('large')                    
                    ->description(fn ($record): string => $record->details)                    
                    ->wrap()
                    ->alignJustify()
                    ->weight('bold'),
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
            'index' => Pages\ListPrestations::route('/'),
            'create' => Pages\CreatePrestation::route('/create'),
            'edit' => Pages\EditPrestation::route('/{record}/edit'),
        ];
    }
}
