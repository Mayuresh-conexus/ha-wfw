<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Gate;

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

            Forms\Components\Toggle::make('isactive')
                ->label('Active Status')
                ->inline()
                ->default(true)
                ->onIcon('heroicon-o-check-circle')
                ->offIcon('heroicon-o-x-circle'),

            Forms\Components\TextInput::make('password')
                ->password()
                ->required(fn ($livewire) => $livewire instanceof Pages\CreateUser)
                ->dehydrateStateUsing(fn ($state) => bcrypt($state)),

            // Role dropdown
            Forms\Components\Select::make('roles')
    ->label('Role')
    ->options(Role::pluck('name', 'name'))
    ->multiple() // remove if single role per user
    ->required()
    ->afterStateHydrated(function ($component, $state, $record) {
        if ($record) {
            $component->state($record->roles->pluck('name')->toArray());
        }
    })
    ->saveRelationshipsUsing(function ($record, $state) {
        $record->syncRoles($state);
    }),

        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('email')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('mobile')->sortable(),
                Tables\Columns\IconColumn::make('isactive')
                    ->boolean()
                    ->label('Active'),
                Tables\Columns\TextColumn::make('created_at')->dateTime()->label('Created'),
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
