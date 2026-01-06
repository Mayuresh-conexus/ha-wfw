<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SymptomResource\Pages;
use App\Models\Symptom;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteAction;
use Illuminate\Support\Facades\Gate;
use Filament\Forms\Components\Select;

class SymptomResource extends Resource
{
    protected static ?string $model = Symptom::class;
    protected static ?string $navigationIcon = 'heroicon-o-exclamation-triangle';
    protected static ?int $navigationSort = 7;

    public static function getNavigationBadge(): ?string
    {
        return (string) Symptom::count();
    }

      public static function shouldRegisterNavigation(): bool
{
    return Gate::allows('view_any_' . static::getModelLabel());
}

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label('Symptom Name')
                    ->required()
                    ->maxLength(255),

                Select::make('body_section_id') // The name of the column in the symptoms table
    ->label('Body Sections')
    ->relationship('bodysection', 'name') // Reference the relationship method 'bodysection' defined in Symptom
    ->searchable()
    ->nullable(),

                TextInput::make('tag')
                    ->label('Tag')
                    ->nullable()
                    ->maxLength(255),

              
                    ToggleButtons::make('is_active')
                        ->label('Active')
                        ->options([1 => 'Active', 0 => 'Inactive'])
                        ->colors([1 => 'success', 0 => 'danger'])
                        ->inline()
                        ->default(1),

                    ToggleButtons::make('iscritical')
                        ->label('Critical')
                        ->options([1 => 'Yes', 0 => 'No'])
                        ->colors([1 => 'danger', 0 => 'secondary'])
                        ->inline()
                        ->default(0),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->sortable(),
                TextColumn::make('name')->sortable()->searchable(),
                TextColumn::make('type')->sortable(),
                TextColumn::make('tag')->sortable(),

                BadgeColumn::make('is_active')
                    ->label('Active')
                    ->getStateUsing(fn ($record) => $record->is_active ? 'Active' : 'Inactive')
                    ->colors([
                        'success' => fn ($state) => $state === 'Active',
                        'danger' => fn ($state) => $state === 'Inactive',
                    ]),

                BadgeColumn::make('iscritical')
                    ->label('Critical')
                    ->getStateUsing(fn ($record) => $record->iscritical ? 'Yes' : 'No')
                    ->colors([
                        'danger' => fn ($state) => $state === 'Yes',
                        'secondary' => fn ($state) => $state === 'No',
                    ]),

                TextColumn::make('created_at')->dateTime('d M Y H:i')->label('Created'),
            ])
            ->filters([])
            ->headerActions([
                CreateAction::make()
                    ->label('New Symptom')
                    ->modalHeading('Create Symptom')
                    ->modalWidth('lg')
                    ->createAnother(true),
            ])
            ->actions([
                EditAction::make()
                    ->modalHeading('Edit Symptom')
                    ->modalWidth('lg'),

                DeleteAction::make()
                    ->modalHeading('Delete Symptom')
                    ->modalSubheading('Are you sure you want to delete this record?')
                    ->requiresConfirmation(),
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
            'index' => Pages\ListSymptoms::route('/'),
            // Remove create/edit pages as modals handle them
        ];
    }
}
