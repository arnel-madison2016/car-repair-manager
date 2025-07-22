<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ParameterResource\Pages;
use App\Filament\Resources\ParameterResource\RelationManagers;

use App\Models\Parameter;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\FileUpload;
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

class ParameterResource extends Resource
{
    protected static ?string $model = Parameter::class;

    protected static ?string $navigationIcon = 'heroicon-o-cog-8-tooth';
    protected static ?string $navigationGroup = 'Settings Management';
    public static function form(Form $form): Form {

        return $form
            ->schema([
                
                TextInput::make('name')
                    ->label('Nom')
                    ->required()
                    ->maxLength(255)
                    ->required(),

                TextInput::make('bank_account')
                    ->label('N˚ compte bancaire')
                    ->required()
                    ->maxLength(255)
                    ->required(),

                TextInput::make('email')
                    ->label('Adresse email')
                    ->email()
                    ->maxLength(255)
                    ->required(),

                TextInput::make('phone')
                    ->label('Nº téléphone')
                    ->tel()
                    ->maxLength(50)
                    ->required(),

                TextInput::make('url_site')
                    ->label('Site web')
                    ->url()
                    ->maxLength(255)
                    ->required(),

                FileUpload::make('url_logo')
                    ->image()
                    ->directory('/images/logos')
                    ->visibility('public')
                    ->preserveFilenames(),

                Textarea::make('adresse')
                    ->label('Adresse')
                    ->rows(5)
                    ->cols(20)
                    ->required()
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                ImageColumn::make('url_logo')
                    ->label('Logo')
                    ->getStateUsing(fn($record) => asset('storage/' . $record->url_logo))
                    ->circular()                                        // Optionnel : rend l’image ronde
                    ->size(130),

                TextColumn::make('name')
                    ->label('Name')
                    ->weight('bold')
                    ->size('large')
                    ->formatStateUsing(fn($state) => ucwords($state)),

                TextColumn::make('bank_account')
                    ->label('Nº Compte bancaire')
                    ->size('large')
                    ->formatStateUsing(fn($state) => strtoupper($state)),
                
                TextColumn::make('email')
                    ->label('Adresse')
                    ->alignJustify()
                    ->formatStateUsing(fn ($state, $record) => 
                        "📞 {$record->phone} <br> 📧  {$record->email} <br> 🌐 {$record->url_site} <br> 📍 {$record->adresse}"
                    )
                    ->html(), // important pour que le HTML soit rendu
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
            'index' => Pages\ListParameters::route('/'),
            'create' => Pages\CreateParameter::route('/create'),
            'edit' => Pages\EditParameter::route('/{record}/edit'),
        ];
    }
}
