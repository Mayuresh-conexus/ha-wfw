<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Spatie\Permission\Models\Role; // Correct import for the Role model
use Illuminate\Support\Facades\Gate;
use Filament\Forms\Components\Select;

class UserResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?string $navigationIcon = 'heroicon-o-user';
    protected static ?string $navigationGroup = 'Filament Shield';

    public static function getNavigationBadge(): ?string
    {
        return (string) User::count();
    }

    public static function shouldRegisterNavigation(): bool
    {
        return Gate::allows('view_any_' . static::getModelLabel());
    }

    public static function form(Form $form): Form
{
    return $form->schema([
        Forms\Components\TextInput::make('name')
            ->required()
            ->maxLength(255),

        Forms\Components\TextInput::make('email')
            ->email()
            ->required()
            ->unique(User::class, 'email', ignoreRecord: true),

        Forms\Components\TextInput::make('mobile')
            ->tel()
            ->maxLength(20),

        Forms\Components\TextInput::make('address')
            ->maxLength(255),

        Forms\Components\TextInput::make('nlnumber')
            ->maxLength(50),

        Forms\Components\TextInput::make('proofid')
            ->maxLength(100),

        

        Forms\Components\TextInput::make('password')
            ->password()
            ->required(fn ($livewire) => $livewire instanceof Pages\CreateUser)
            ->dehydrateStateUsing(fn ($state) => bcrypt($state)),

        // Role dropdown
        Forms\Components\Select::make('roles')
            ->label('Roles')
            ->options(Role::pluck('name', 'name'))
            ->multiple()
            ->required()
            ->afterStateHydrated(function ($component, $state, $record) {
                if ($record) {
                    $component->state($record->roles->pluck('name')->toArray());
                }
            })
            ->saveRelationshipsUsing(function ($record, $state) {
                // Sync roles to the user
                $record->syncRoles($state); 
            }),

        // Doctors selection
        Forms\Components\Select::make('doctors')
            ->label('Doctors')
            ->options(fn () => User::role('doctor')->pluck('name', 'id'))
            ->multiple()
            ->searchable()
            ->preload()
            ->nullable()
            ->afterStateHydrated(function ($component, $state, $record) {
                if ($record && $record->doctorid) {
                    $component->state($record->doctorid);  // Set previously selected doctors' IDs
                }
            })
            ->saveRelationshipsUsing(function ($record, $state) {
                // Save the selected doctor IDs in the `doctorid` column (JSON array)
                $record->doctorid = $state; 
                $record->save();
            }),
        
        Select::make('gender')
                    ->options(['Male' => 'Male', 'Female' => 'Female', 'Other' => 'Other']),

        Forms\Components\Toggle::make('isactive')
            ->label('Active Status')
            ->inline()
            ->default(true)
            ->onIcon('heroicon-o-check-circle')
            ->offIcon('heroicon-o-x-circle'),
    ]);
}


    public static function table(Table $table): Table
{
    return $table
        ->columns([
            Tables\Columns\TextColumn::make('name')->sortable()->searchable(),
            Tables\Columns\TextColumn::make('email')->sortable()->searchable()->toggleable(),
            Tables\Columns\TextColumn::make('mobile')->sortable()->toggleable(),
            Tables\Columns\TextColumn::make('created_at')->dateTime()->label('Created')->toggleable(),

            // Display Roles (comma-separated)
            Tables\Columns\TextColumn::make('roles.name') // Assuming the 'roles' relationship is set up correctly
                ->label('Roles')
                ->sortable()
                ->toggleable()
                ->getStateUsing(function ($record) {
                    // Safely handle roles being null
                    return $record->roles->isNotEmpty() ? $record->roles->pluck('name')->implode(', ') : 'No roles assigned';
                }),

            // Display Doctors (comma-separated names)
            Tables\Columns\TextColumn::make('doctorid') // Assuming 'doctorid' is an array of doctor IDs
                ->label('Doctors')
                ->toggleable()
                ->getStateUsing(function ($record) {
                    // Safely handle doctorid being null or empty
                    if (!empty($record->doctorid)) {
                        // Get doctor names based on the doctor IDs
                        return User::whereIn('id', $record->doctorid)->pluck('name')->implode(', ');
                    }
                    return 'No doctors assigned';
                }),
            Tables\Columns\IconColumn::make('isactive')
                ->boolean()
                ->label('Active')
        ])
        ->filters([])
        ->actions([
            Tables\Actions\EditAction::make(),
        ])
        ->bulkActions([
            Tables\Actions\DeleteBulkAction::make(),
        ]);
}



    public static function getRelations(): array
    {
        return [];
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
