<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BrandResource\Pages;
use App\Filament\Resources\BrandResource\RelationManagers;

use App\Models\Brand;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms;
use Filament\Forms\Form;

use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\ColumnGroup;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Parfaitementweb\FilamentCountryField\Forms\Components\Country;

class BrandResource extends Resource
{
    protected static ?string $model = Brand::class;

    protected static ?string $navigationIcon = 'heroicon-o-truck';
    protected static ?string $navigationGroup = 'Services Management';

    public static function getNavigationBadge(): ?string {

        return static::getModel()::count();        
    }
    public static function form(Form $form): Form {

        return $form
            ->schema([

                TextInput::make('name')
                    ->label('Name')
                    ->required()
                    ->maxLength(255)
                    ->required(),

                Select::make('manufacturer_id')
                    ->relationship('manufacturer', 'name')
                    ->searchable()
                    ->preload()
                    ->createOptionForm([

                        TextInput::make('name')
                            ->label('Constructeur')
                            ->required()
                            ->maxLength(150)
                            ->required(),

                        Country::make('country'),
                    ]),

                    FileUpload::make('url_picture')
                        ->label('Logo')
                        ->image()
                        ->directory('/images/brands')
                        ->visibility('public')
                        ->preserveFilenames()
                        ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                
                ImageColumn::make('url_picture')
                        ->label('Logo')
                        ->getStateUsing(fn($record) => asset('storage/' . $record->url_picture))
                        ->circular()                                        // Optionnel : rend l’image ronde
                        ->size(50),

                TextColumn::make('name')
                    ->label('Name')
                    ->formatStateUsing(fn($state) => strtoupper($state)),

                ColumnGroup::make('Constructeur', [
                    TextColumn::make('manufacturer.name')
                        ->label('groupe')
                        ->formatStateUsing(fn($state) => strtoupper($state))
                        ->sortable()
                        ->searchable(),
                    
                    TextColumn::make('manufacturer.country')
                        ->label('pays')
                        ->formatStateUsing(fn($state) => strtoupper($state))
                        ->sortable()
                        ->searchable(),
                ]),
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
            'index' => Pages\ListBrands::route('/'),
            'create' => Pages\CreateBrand::route('/create'),
            'edit' => Pages\EditBrand::route('/{record}/edit'),
        ];
    }
}
