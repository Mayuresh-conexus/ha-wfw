<?php

namespace App\Filament\Resources\PatientResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;
use Filament\Forms\Form;

class RecordsRelationManager extends RelationManager
{
    protected static string $relationship = 'records';

    protected static ?string $title = 'Patient Records'; // Optional custom title

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('doctorid')
                    ->relationship('doctor', 'name')
                    ->label('Doctor')
                    ->required(),

                Forms\Components\Select::make('volunteerid')
                    ->relationship('volunteer', 'name')
                    ->label('Volunteer'),

                Forms\Components\Select::make('projectid')
                    ->relationship('project', 'name')
                    ->label('Project'),

                Forms\Components\Select::make('programid')
                    ->relationship('program', 'name')
                    ->label('Program'),

                Forms\Components\Select::make('record_type')
                    ->label('Record Type')
                    ->options([
                        'initial' => 'Initial',
                        'followup' => 'Follow-up',
                        'final' => 'Final',
                    ])
                    ->required(),

                Forms\Components\Textarea::make('notes')
                    ->label('Notes')
                    ->rows(3),

                Forms\Components\FileUpload::make('attachments')
                    ->label('Attachments')
                    ->multiple()
                    ->directory('records/attachments'),

                Forms\Components\Select::make('status')
                    ->options([
                        'draft' => 'Draft',
                        'submitted' => 'Submitted',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                    ])
                    ->label('Status')
                    ->default('draft'),

                Forms\Components\DateTimePicker::make('submitted_at')
                    ->label('Submitted At'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('record_type')
                    ->label('Type')
                    ->badge(),

                Tables\Columns\TextColumn::make('doctor.name')
                    ->label('Doctor')
                    ->sortable(),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->colors([
                        'primary' => 'draft',
                        'info' => 'submitted',
                        'success' => 'approved',
                        'danger' => 'rejected',
                    ]),

                Tables\Columns\TextColumn::make('submitted_at')
                    ->label('Submitted On')
                    ->dateTime('d M Y, h:i A')
                    ->sortable(),

                Tables\Columns\TextColumn::make('notes')
                    ->label('Notes')
                    ->limit(40)
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'draft' => 'Draft',
                        'submitted' => 'Submitted',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                    ]),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
}
