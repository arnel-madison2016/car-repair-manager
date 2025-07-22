<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;

use Filament\Resources\Resource;
use Filament\Support\Enums\FontWeight;
use Filament\Forms\Components\Section;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\IconColumn;

use Filament\Tables\Columns\TextColumn;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Spatie\Permission\Models\Role;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-circle';
    protected static ?string $navigationGroup = 'Contacts Management';
    protected static ?string $navigationLabel = 'Users';

    protected static ?string $modelLabel = 'Users';

     public static function query(): Builder {

        // Exclure les administrateurs (par leur rôle)
        return parent::query()
            ->whereDoesntHave('roles', fn ($q) => $q->where('name', 'admin'));
    }    

    public static function form(Form $form): Form {

        return $form
            ->schema([
                Section::make('Informations générales')
                    ->description('Enter the details of the repair sheet here')

                    ->schema([
                        TextInput::make('name')
                            ->required(),

                        TextInput::make('email')
                            ->email()
                            ->required(),

                        Select::make('roles')
                            ->label('Rôles')
                            ->multiple()
                            ->preload()
                            ->relationship('roles', 'name')
                            ->searchable()
                            ->options(function () {
                                return Role::where('name', '!=', 'admin')
                                    ->pluck('name', 'id');
                            })
                            ->default(fn ($record) => $record?->roles()->where('name', '!=', 'admin')->pluck('id')->toArray()),

                        Toggle::make('is_active')
                            ->label('Compte actif')
                            ->inline(false)
                            ->onIcon('heroicon-m-bolt')
                            ->offIcon('heroicon-m-user')
                            ->onColor('success')
                            ->offColor('danger')
                            ->accepted(),        
                    ])->columns(2)                
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->formatStateUsing(function ($state, $record) {
                        $name = ucwords($record->name);
                        $email = $record->email;

                        return "<b>{$name}</b> <br> {$email}";
                    })
                    ->html(),

                TextColumn::make('roles.name')
                    ->label('Rôles')
                    ->badge()
                    ->colors([
                        'primary',
                        'secondary',
                        'neural',
                        'gray' => 'accountant',
                        'success' => 'manager',
                        'warning' => 'mechanic',
                        'info' => 'client',
                        'danger' => 'admin',
                        // ajoute tes couleurs si besoin
                    ]),
                    
                IconColumn::make('is_active')
                    ->label('Etat')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),                
            ])
            ->filters([
                //
            ])
            ->actions([
                //Tables\Actions\EditAction::make(),

                Action::make('toggle_active')
                ->label(fn (User $record) => $record->is_active ? 'Désactiver' : 'Activer')
                ->color(fn (User $record) => $record->is_active ? 'danger' : 'success')
                ->requiresConfirmation()
                ->action(function (User $record) {
                    $record->is_active = !$record->is_active;
                    $record->save();
                }),

                Action::make('edit_roles')
                ->label('Modifier les rôles')
                ->icon('heroicon-o-users')
                ->form([
                    Select::make('roles')
                        ->label('Rôles')
                        ->multiple()
                        ->options(
                            Role::where('name', '!=', 'admin')->pluck('name', 'id')
                        )
                        ->searchable()
                        ->preload()
                        ->required(),
                ])
                ->action(function (array $data, $record) {
                    $roles = Role::whereIn('id', $data['roles'])->pluck('name')->toArray();
                    $record->syncRoles($roles);
                }),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
