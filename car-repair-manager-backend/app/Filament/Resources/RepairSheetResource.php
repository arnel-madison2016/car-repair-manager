<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RepairSheetResource\Pages;
use App\Filament\Resources\RepairSheetResource\RelationManagers;
use App\Models\Brand;
use App\Models\CarModel;
use App\Models\Customer;
use App\Models\RepairSheet;
use App\Models\Vehicule;
use App\Models\Service;
use App\Models\Task;
use App\Models\User;
use Carbon\Carbon;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;

use Filament\Resources\Resource;
use Filament\Support\Enums\FontWeight;
use Filament\Forms\Components\Section;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\ColumnGroup;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RepairSheetResource extends Resource
{
    protected static ?string $model = RepairSheet::class;

    protected static ?string $navigationIcon = 'heroicon-o-wrench';

    protected static ?string $navigationGroup = 'Repair cars Management';

    public static function getNavigationBadge(): ?string {

        return static::getModel()::count();        
    }
    public static function getNavigationBadgeColor(): string|array|null {
        return 'success';
    }
    public static function form(Form $form): Form {

        return $form
            ->schema([
                
                Section::make('Informations générales')
                    ->description('Enter the details of the repair sheet here')

                    ->schema([

                        DatePicker::make('date_sortie')
                            ->label('Date remise probable')
                            ->displayFormat('d F Y')
                            ->locale('fr')
                            ->native(false)
                            ->required(),

                        Select::make('status')
                            ->label('Status')
                            ->options([
                                'pending' => 'En attente',
                                'in process' => 'En cours d\'exécution',
                                'delivered' => 'Livrée',
                            ]),

                        RichEditor::make('probleme_reporte')
                            ->label('Problèmes rapportés')
                            ->columnSpanFull()
                            ->required()
                            ->toolbarButtons([
                                'bold',
                                'italic',
                                'underline',
                                'strike',
                                'bulletList',
                                'orderedList',
                                'blockquote',
                                'link',
                                'undo',
                                'redo',
                                // Ajout des alignements
                                'alignLeft',
                                'alignCenter',
                                'alignRight',
                                'alignJustify',
                            ]),
                    ])
                    ->columns(2),

                Section::make('Véhicule')
                    ->description('Enter the details of the vehicle to be repaired here')

                    ->schema([

                        Select::make('vehicule_id')
                            ->label('Véhicule')
                            ->relationship('vehicule', 'licence_plate')
                            ->getOptionLabelFromRecordUsing(fn (Vehicule $record) => strtoupper("{$record->car_model->brand->name} - {$record->car_model->name} - {$record->license_plate}")) // Affiche "Marque - Immatriculation"
                            ->searchable() // Permet la recherche
                            ->preload() // Charge les données en avance
                            ->required()
                            ->columnSpanFull()
                            ->createOptionForm([

                                Section::make('Vehicule')
                                    ->description('Enter the details of the vehicle')

                                    ->schema([

                                        Select::make('brand_id')
                                            ->label('Constructeur')
                                            ->options(Brand::all()->pluck('name', 'id'))
                                            ->reactive()
                                            ->afterStateUpdated(fn (callable $set) => $set('car_model_id', null)),

                                        Select::make('car_model_id')
                                            ->label('Marque')
                                            ->options(fn (callable $get) => 
                                                CarModel::where('brand_id', $get('brand_id'))
                                                    ->pluck('name', 'id')
                                            )
                                            ->reactive()
                                            ->afterStateUpdated(fn (callable $set) => $set('vehicule_id', null))
                                            ->required(),

                                        TextInput::make('license_plate')
                                            ->label('Nº immatriculation')
                                            ->required()
                                            ->maxLength(150),

                                        TextInput::make('chassis_number')
                                            ->label('Nº chassis')
                                            ->required()
                                            ->maxLength(150)
                                            ->required(),

                                        TextInput::make('odometer_reading')
                                            ->label('Compteur kilometrique')
                                            ->required()
                                            ->numeric()
                                            ->maxLength(50)
                                            ->required(),

                                        TextInput::make('year_registration')
                                            ->label('Année de mise en service')
                                            ->required()
                                            ->numeric()
                                            ->mask('9999')
                                            ->maxLength(4),

                                        Select::make('fuel_type')
                                            ->label('Type de carburant')
                                            ->options([
                                                'essence électricité (hybride rechargeable)' => 'Essence électricité (hybride rechargeable)',
                                                'bicarburation essence-GPL' => 'Bicarburation essence-GPL',
                                                'essence-électricité (hybride non rechargeable)' => 'Essence-électricité (hybride non rechargeable)',
                                                'electricité' => 'Electricité',
                                                'bicarburation essence-gaz naturel et électricité (hybride rechargeable)' => 'Bicarburation essence-gaz naturel et électricité (hybride rechargeable)',
                                                'bicarburation essence-gaz naturel' => 'Bicarburation essence-gaz naturel',
                                                'essence' => 'Essence',
                                                'ethanol' => 'Ethanol',
                                                'gazogène (*)' => 'Gazogène (*)',
                                                'gaz naturel' => 'Gaz naturel',
                                                'gazole (ou diesel)' => 'Gazole (ou diesel)',
                                                'gaz de pétrole liquéfié GPL' => 'Gaz de pétrole liquéfié GPL',
                                                'autres hydrocarbures gazeux comprimés' => 'Autres hydrocarbures gazeux comprimés',
                                                'hydrogène' => 'Hydrogène',
                                                'gaz naturel-électricité (hybride rechargeable)' => 'Gaz naturel-électricité (hybride rechargeable)',
                                                'gaz naturel-électricité (hybride non rechargeable)' => 'Gaz naturel-électricité (hybride non rechargeable)',
                                                'monocarburation GPL-électricité (hybride rechargeable)' => 'Monocarburation GPL-électricité (hybride rechargeable)',
                                                'monocarburation GPL-électricité (hybride non rechargeable)' => 'Monocarburation GPL-électricité (hybride non rechargeable)',
                                                'pétrole lampant' => 'Pétrole lampant',
                                            ]),

                                        Select::make('gear_box')
                                            ->label('Transmission')
                                            ->options([
                                                'boite automatique' => 'Boîte automatique',
                                                'boite manuelle' => 'Boîte manuelle',
                                                'boite sequentielle' => 'Boîte séquentielle',
                                            ]),

                                        TextInput::make('engine_size')
                                            ->label('Cylindrée')
                                            ->required()
                                            ->numeric()
                                            ->maxLength(50),

                                        Select::make('customer_id')
                                            ->label('Proprietaire')
                                            ->options(fn () => Customer::all()->pluck('full_name', 'id'))                                            
                                            ->searchable()
                                            ->preload(),

                                        FileUpload::make('url_pictures')
                                            ->image()
                                            ->disk('public')
                                            ->directory('images/vehicules')
                                            ->preserveFilenames()
                                            ->label('Apercu du vehicule')
                                            ->columnSpanFull(),
                                    ])
                                    ->columns(2),
                            ]),                                                
                    ])
                    ->columns(2),

                Section::make('Tâches assignées')
                    ->description('Enter here the list of tasks assigned to the mechanic')

                    ->schema([

                        Repeater::make('tasks')
                            ->label('')
                            ->relationship()
                            ->schema([

                                Select::make('service_id')
                                    ->label('Prestation')
                                    ->options(Service::pluck('name', 'id'))
                                    ->required(),

                                Select::make('user_id')
                                    ->label('Mécanicien assigné')
                                    ->options(function () {
                                        return User::mechanics()
                                            ->get()
                                            ->pluck('name', 'id')
                                            ->mapWithKeys(fn ($name, $id) => [$id => strtoupper($name)]);;
                                    })
                                    ->required(),

                                DatePicker::make('date_completed')
                                    ->label('A terminer le')
                                    ->displayFormat('d F Y')
                                    ->locale('fr')
                                    ->native(false)
                                    ->required(),

                                Select::make('status')
                                    ->label('Status')
                                    ->options([
                                        'pending' => 'En attente',
                                        'in process' => 'En cours d\'exécution',
                                        'completed' => 'Terminée',
                                    ]),

                                TimePicker::make('estimated_time')
                                    ->label('Temps estimé')
                                    ->required(),

                                TextArea::make('remarks')
                                    ->label('Observations')
                                    ->rows(4),
                            ])
                            ->columns(2)
                            ->defaultItems(1)
                            ->collapsible()
                            ->addActionLabel('Ajouter une tâche'),
                ]),
            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                ColumnGroup::make('Détails du véhicule', [
                    ImageColumn::make('vehicule.url_pictures')
                        ->label('')
                        ->getStateUsing(fn($record) => asset('storage/' . $record->vehicule->url_pictures))
                        ->circular()                                            // Optionnel : rend l’image ronde
                        ->size(85),

                    TextColumn::make('vehicule.id')
                        ->label('')
                        ->weight(FontWeight::Bold)
                        ->formatStateUsing(function ($record) {
                            $vehicule = $record->vehicule;
                            if (!$vehicule) {
                                return '-';
                            }

                            $carModel = $vehicule->car_model;
                            $brand = strtoupper($carModel->brand->name);
                            $modelName = strtoupper($carModel?->name ?? 'Modèle inconnu');
                            $plate = strtoupper($vehicule->license_plate ?? '-');
                            $chassis = strtoupper($vehicule->chassis_number ?? 'Châssis ?');

                            if (!$plate) {
                                return "{$brand} • {$modelName} <br/> <span class='text-sm text-gray-500 font-normal'>{$chassis}</span>";
                            }else{
                                return "{$brand} • {$modelName} <br/> <span class='text-sm text-gray-500 font-normal'>{$plate}</pan>";
                            }                            
                        })
                        ->html(),
                ]),

                ColumnGroup::make('Détails de la panne', [

                    TextColumn::make('date_arrivee')
                        ->label('')
                        ->formatStateUsing(function ($record) {
                            $datetime = Carbon::createFromFormat('Y-m-d', "{$record->date_arrivee}")
                                ->locale('fr');

                            $date = ucwords($datetime->isoFormat('dddd D MMMM YYYY')); // Exemple : "Mardi 8 juillet 2025"

                            $probleme = e($record->probleme_reporte);
                            
                            return "Date dépot: <span class='text-base text-black-500 font-bold mt-2 mb-4'> {$date}</span> <br/> <span class='block text-sm text-gray-500 font-normal leading-relaxed line-clamp-5 text-justify'>{$record->probleme_reporte}</span>";
                        })
                        ->wrap()
                        ->html(),

                    TextColumn::make('tasks.user_id')
                        ->label('Mécaniciens affectés')
                        ->formatStateUsing(function ($record) {
                            // Récupère tous les users des tâches liées
                            $mecaniciens = $record->tasks
                                ->filter(fn ($task) => $task->mechanic && $task->mechanic->hasRole('mechanic'))
                                ->map(fn ($task) => ucwords($task->mechanic->name))
                                ->unique()
                                ->implode('<br/>');

                            if (empty($mecaniciens)) {
                                return "<span class='text-gray-400 italic'>Aucun mécanicien assigné</span>";
                            }

                            return "<span class='text-sm text-gray-800 font-bold'>{$mecaniciens}</span>";
                        })
                        ->html(),
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
            'index' => Pages\ListRepairSheets::route('/'),
            'create' => Pages\CreateRepairSheet::route('/create'),
            'edit' => Pages\EditRepairSheet::route('/{record}/edit'),
        ];
    }
}
