<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ClientResource\Pages;
use App\Filament\Resources\ClientResource\RelationManagers;

use App\Models\Customer;

use Filament\Forms;
use Filament\Forms\Form;
use Illuminate\Support\Str;
use Filament\Forms\Components\FileUpload;
use Illuminate\Support\Facades\Storage;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;

use Filament\Resources\Resource;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Parfaitementweb\FilamentCountryField\Forms\Components\Country;

class ClientResource extends Resource
{
    protected static ?string $model = Customer::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    public static function form(Form $form): Form {
        return $form

            ->schema([

                TextInput::make('first_name')
                    ->label('Prénoms')
                    ->required()
                    ->maxLength(150)
                    ->required(),

                TextInput::make('last_name')
                    ->label('Noms')
                    ->required()
                    ->maxLength(150)
                    ->required(),

                Select::make('gender')
                    ->label('Sexe')
                    ->options([
                        'f' => 'Féminin',
                        'm' => 'Masculin',
                ]),

                TextInput::make('email')
                    ->label('Adresse email')
                    ->email()
                    ->maxLength(255)
                    ->required(),

                Country::make('country')
                    ->label('Pays')
                    ->native(false),

                TextInput::make('city')
                    ->label('Ville')
                    ->maxLength(50)
                    ->required(),

                TextInput::make('phone')
                    ->label('Nº téléphone')
                    ->tel()
                    ->maxLength(50)
                    ->required(),                

                TextInput::make('postal_code')
                    ->label('Code postal')
                    ->maxLength(150)
                    ->required(),

                TextInput::make('profession')
                    ->label('profession')
                    ->maxLength(255),

                TextInput::make('company_name')
                    ->label('Nom de l\'entreprise')
                    ->maxLength(255),

                Textarea::make('adresse')
                    ->label('Adresse')
                    ->rows(5)
                    ->cols(20)
                    ->required(),

                FileUpload::make('url_photo')
                    ->label('Photo')
                    ->image()
                    ->disk('public')
                    ->directory('images/customers')
                    ->preserveFilenames()
                    ->imageEditor()
                    ->downloadable()
                    ->openable()
                    ->nullable()
                    ->helperText('Formats jpg, png, jpeg.'),

            ]);
    }

    public static function table(Table $table): Table {
        
        return $table
            ->columns([

                ImageColumn::make('url_photo')
                        ->label('')
                        //->getStateUsing(fn($record) => asset('storage/' . $record->url_photo))
                        ->getStateUsing(function ($record) {
                            return $record->url_photo
                                ? asset('storage/' . $record->url_photo)
                                : 'https://ui-avatars.com/api/?name=' . urlencode($record->full_name);
                        })
                        ->circular()
                        ->size(90),
              
                TextColumn::make('full_name')
                    ->label('Noms et Prenoms')
                    ->weight('bold')
                    ->size('large')                    
                    ->formatStateUsing(fn($state) => ucwords($state))
                    ->searchable(),
                
                TextColumn::make('email')
                    ->label('Adresse')
                    ->formatStateUsing(function ($state, $record) {
                        $adresse = ucwords(strtolower($record->adresse));

                        return "📞 {$record->phone} <br> 📧 {$record->email} <br> 📍 {$adresse}";
                    })
                    ->html(),

                TextColumn::make('city')
                    ->label('Filiations')
                    ->formatStateUsing(function ($state, $record) {
                        $profession = ucfirst($record->profession);
                        $city = ucwords(strtolower($record->city));
                        $country = strtoupper($record->country);

                        return "<b>{$profession}</b> <br> {$city}/{$country}";
                    })
                    ->html(),
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
            'index' => Pages\ListClients::route('/'),
            'create' => Pages\CreateClient::route('/create'),
            'edit' => Pages\EditClient::route('/{record}/edit'),
        ];
    }
}
