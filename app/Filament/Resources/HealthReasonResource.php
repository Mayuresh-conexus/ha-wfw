<?php

namespace App\Filament\Resources;

use App\Filament\Resources\HealthReasonResource\Pages;
use App\Models\HealthReason;
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

class HealthReasonResource extends Resource
{
    protected static ?string $model = HealthReason::class;

    protected static ?string $navigationIcon = 'heroicon-o-heart';
    protected static ?int $navigationSort = 4;

    public static function getNavigationBadge(): ?string
    {
        return (string) HealthReason::count();
    }



    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make(2)->schema([
                    TextInput::make('name')
                        ->label('Health Reason')
                        ->required()
                        ->maxLength(255),

                    ToggleButtons::make('is_active')
                        ->label('Status')
                        ->options([
                            1 => 'Active',
                            0 => 'Inactive',
                        ])
                        ->colors([
                            1 => 'success',
                            0 => 'danger',
                        ])
                        ->inline()
                        ->default(1),
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->sortable(),
                TextColumn::make('name')->sortable()->searchable(),

                BadgeColumn::make('is_active')
                    ->label('Status')
                    ->getStateUsing(fn ($record) => $record->is_active ? 'Active' : 'Inactive')
                    ->colors([
                        'success' => fn ($state) => $state === 'Active',
                        'danger' => fn ($state) => $state === 'Inactive',
                    ]),

                TextColumn::make('created_at')
                    ->dateTime('d M Y H:i')
                    ->label('Created')->toggleable(),
            ])
            ->filters([])
            ->headerActions([
                CreateAction::make()
                    ->label('New Health Reason')
                    ->modalHeading('Create Health Reason')
                    ->modalWidth('lg')
                    ->createAnother(true),
            ])
            ->actions([
                EditAction::make()
                    ->modalHeading('Edit Health Reason')
                    ->modalWidth('lg'),

                DeleteAction::make()
                    ->modalHeading('Delete Health Reason')
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
            'index' => Pages\ListHealthReasons::route('/'),
            // Remove create/edit pages as modals handle them
        ];
    }
}
