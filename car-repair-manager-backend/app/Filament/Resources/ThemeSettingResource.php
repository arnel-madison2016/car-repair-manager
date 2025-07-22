<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ThemeSettingResource\Pages;
use App\Filament\Resources\ThemeSettingResource\RelationManagers;
use App\Models\ThemeSetting;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\View;
use Illuminate\Support\HtmlString;
use Livewire\Livewire;

use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ThemeSettingResource extends Resource
{
    protected static ?string $model = ThemeSetting::class;

    protected static ?string $navigationIcon = 'heroicon-o-paint-brush';
    protected static ?string $navigationGroup = 'Settings Management';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                    TextInput::make('name')
                        ->required(),                   

                    ColorPicker::make('primary_color')
                        ->label('Couleur principale')
                        ->live(onBlur: true)
                        ->afterStateUpdated(fn ($state, $set) => $set('preview.primaryColor', $state)),

                    Select::make('font_family')
                        ->label('Police de caractères')
                        ->options([
                            'Arial, sans-serif' => 'Arial',
                            'Verdana, sans-serif' => 'Verdana',
                            '"Times New Roman", serif' => 'Times New Roman',
                            'Georgia, serif' => 'Georgia',
                            '"Courier New", monospace' => 'Courier New',
                            '"Open Sans", sans-serif' => 'Open Sans',
                            '"Roboto", sans-serif' => 'Roboto',
                            '"Poppins", sans-serif' => 'Poppins',
                            '"Montserrat", sans-serif' => 'Montserrat',
                        ])
                        //->searchable()
                        ->default('Roboto, sans-serif')
                        ->live(onBlur: true)
                        ->afterStateUpdated(fn ($state, $set) => $set('preview.fontFamily', $state)),

                    ColorPicker::make('secondary_color')
                        ->label('Couleur secondaire')
                        ->live(onBlur: true)
                        ->afterStateUpdated(fn ($state, $set) => $set('preview.secondaryColor', $state)),                                        

                    FileUpload::make('logo_path')
                        ->label('Logo')
                        ->image()
                        ->live(onBlur: true)
                        ->directory('images/themes/logos'),

                    FileUpload::make('favicon_path')
                        ->label('Favicon')
                        ->image()
                        ->live(onBlur: true)
                        ->directory('themes/favicons'),

                    Textarea::make('custom_css')
                        ->label('CSS personnalisé')
                        ->live(onBlur: true),

                    Toggle::make('dark_mode')
                        ->label('Mode sombre')
                        ->live(onBlur: true)
                        ->afterStateUpdated(fn ($state, $set) => $set('preview.darkMode', $state)),
                        
                    Placeholder::make('previewArea')

                        ->label('Aperçu du thème')
                        ->content(function (callable $get) {
                            return new HtmlString(
                                Livewire::mount('theme-preview', [
                                    'primaryColor'    => $get('primary_color') ?? '#3b82f6',
                                    'secondaryColor'  => $get('secondary_color') ?? '#6366f1',
                                    'fontFamily'      => $get('font_family') ?? 'Roboto, sans-serif',
                                    'darkMode'        => $get('dark_mode') ?? false,
                                ])
                            );
                        }),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                ImageColumn::make('logo_path')
                    ->label('Logo')
                    ->getStateUsing(fn($record) => asset('storage/' . $record->logo_path))
                    ->circular()                                        // Optionnel : rend l’image ronde
                    ->size(130),

                Tables\Columns\TextColumn::make('name')
                    ->label('Nom')
                    ->weight('bold')
                    ->size('large')
                    ->formatStateUsing(fn($state) => ucwords($state)),

                Tables\Columns\TextColumn::make('primary_color')
                    ->label('Couleur principale')
                    ->badge()
                    ->formatStateUsing(fn (string $state) => '<span style="background-color:' . $state . '; display:inline-block; width:1rem; height:1rem; border-radius:9999px;"></span> ' . $state)
                    ->html(), // très important pour interpréter le HTML,

                Tables\Columns\TextColumn::make('secondary_color')
                    ->label('Couleur secondaire')
                    ->badge()
                    ->formatStateUsing(fn (string $state) => '<span style="background-color:' . $state . '; display:inline-block; width:1rem; height:1rem; border-radius:9999px;"></span> ' . $state)
                    ->html(), // très important pour interpréter le HTML,

                Tables\Columns\TextColumn::make('font_family')
                    ->label('Police')
                    ->weight('bold')
                    ->size('large')
                    ->formatStateUsing(fn($state) => ucwords($state)),

                Tables\Columns\IconColumn::make('dark_mode')
                    ->label('Sombre')
                    ->boolean()
                    ->trueIcon('heroicon-m-moon')
                    ->falseIcon('heroicon-m-sun'),
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
            'index' => Pages\ListThemeSettings::route('/'),
            'create' => Pages\CreateThemeSetting::route('/create'),
            'edit' => Pages\EditThemeSetting::route('/{record}/edit'),
        ];
    }
}
