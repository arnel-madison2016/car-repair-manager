<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AppointmentResource\Pages;
use App\Filament\Resources\AppointmentResource\RelationManagers;

use App\Models\Appointment;
use App\Mail\AppointmentStatusMail;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontWeight;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\ColumnGroup;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AppointmentResource extends Resource
{
    protected static ?string $model = Appointment::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-date-range';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table {
        return $table
            ->columns([

                ColumnGroup::make('Détails du rendez-vous', [
                    TextColumn::make('selected_date')
                        ->label('')
                        ->formatStateUsing(function ($record) {
                            $datetime = Carbon::createFromFormat('Y-m-d H:i:s', "{$record->selected_date} {$record->selected_time}")
                                ->locale('fr');

                            $date = ucwords($datetime->isoFormat('dddd D MMMM YYYY')); // Exemple : "Mardi 8 juillet 2025"
                            $time = $datetime->isoFormat('HH:mm');

                            return "<strong>{$date}</strong> <br>à {$time}";
                        })
                        ->description(fn (Appointment $record): string => $record->notes)
                        ->wrap()
                        ->html()
                        ->sortable()
                        ->searchable(),
                ]),

                ColumnGroup::make('Informations du véhicule', [
                        
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

                ColumnGroup::make('Identité du client', [
                    
                    TextColumn::make('customer.full_name')
                        ->label('')
                        ->weight('bold')
                        ->size('large')                    
                        ->formatStateUsing(function ($record) {
                            $customer = $record->customer;

                            if (!$customer) return '-';

                            //$fullName = trim($customer->first_name . ' ' . $customer->last_name);
                            $name = ucwords($customer->full_name);
                            $adresse = ucwords($customer->adresse ?? 'Adresse non renseignée');

                            // Affiche le nom en gras, adresse en dessous
                            return "<strong>{$name}</strong><br><span class='text-sm text-gray-500 font-normal'>{$adresse}</span>";
                        })
                        ->html()
                        ->searchable(),
                ]),                
            ])
            ->filters([
                //
            ])
            ->actions([
                //Tables\Actions\EditAction::make(),

                Action::make('confirmer')
                    ->label('Confirmer')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->action(function ($record) {
                        $record->update(['status' => 'confirmed']);
                        Mail::to($record->customer->email)->send(new AppointmentStatusMail($record));
                        
                        Notification::make()
                            ->title('Rendez-vous confirmé')
                            ->success()
                            ->send();
                    })
                    ->visible(fn ($record) => $record->status !== 'confirmed' && $record->status !== 'cancelled'),

                Action::make('Annuler')
                    ->label('Annuler')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->action(function ($record) {
                        $record->update(['status' => 'cancelled']);
                       
                        Mail::to($record->customer->email)->send(new AppointmentStatusMail($record));

                        Notification::make()
                            ->title('Rendez-vous annulé')
                            ->danger()
                            ->send();
                    })
                    ->visible(fn ($record) => $record->status !== 'confirmed' && $record->status !== 'cancelled'),
            ])
            ->defaultSort('selected_date', 'asc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'En attente',
                        'confirmed' => 'Confirmé',
                        'cancelled' => 'Annulé',
                    ])
                    ->label('Statut'),
            ])
            ->emptyStateHeading('Aucun rendez-vous en attente')
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
            'index' => Pages\ListAppointments::route('/'),
            //'create' => Pages\CreateAppointment::route('/create'),
            //'edit' => Pages\EditAppointment::route('/{record}/edit'),
        ];
    }
}
