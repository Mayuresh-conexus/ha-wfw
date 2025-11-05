<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BodySectionResource\Pages;
use App\Models\BodySection;
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

class BodySectionResource extends Resource
{
    protected static ?string $model = BodySection::class;
    protected static ?string $navigationIcon = 'heroicon-o-user';
    protected static ?int $navigationSort = 8;

    public static function getNavigationBadge(): ?string
    {
        return (string) BodySection::count();
    }


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label('Body Section')
                    ->required()
                    ->maxLength(255),

                TextInput::make('tag')
                    ->label('Tag')
                    ->nullable()
                    ->maxLength(255),

                Grid::make(2)->schema([
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
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->sortable(),
                TextColumn::make('name')->sortable()->searchable(),
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
                    ->label('New Body Section')
                    ->modalHeading('Create Body Section')
                    ->modalWidth('lg')
                    ->createAnother(true),
            ])
            ->actions([
                EditAction::make()
                    ->modalHeading('Edit Body Section')
                    ->modalWidth('lg'),

                DeleteAction::make()
                    ->modalHeading('Delete Body Section')
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
            'index' => Pages\ListBodySections::route('/'),
            // remove create/edit pages as modals handle them
        ];
    }
}
