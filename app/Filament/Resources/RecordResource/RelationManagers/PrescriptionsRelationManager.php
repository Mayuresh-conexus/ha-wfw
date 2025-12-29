<?php

namespace App\Filament\Resources\RecordResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class PrescriptionsRelationManager extends RelationManager
{
    protected static string $relationship = 'prescriptions';
    protected static ?string $title = 'Prescriptions';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('medicine_name')
                    ->label('Medicine Name')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('dosage')
                    ->label('Dosage')
                    ->maxLength(255),

                Forms\Components\TextInput::make('frequency')
                    ->label('Frequency')
                    ->maxLength(255),

                Forms\Components\Textarea::make('instructions')
                    ->label('Instructions')
                    ->rows(3),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('medicine_name')
            ->columns([
                Tables\Columns\TextColumn::make('medicine_name')->label('Medicine')->sortable(),
                Tables\Columns\TextColumn::make('dosage')->label('Dosage'),
                Tables\Columns\TextColumn::make('frequency')->label('Frequency'),
                Tables\Columns\TextColumn::make('created_at')->dateTime()->label('Added On'),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
