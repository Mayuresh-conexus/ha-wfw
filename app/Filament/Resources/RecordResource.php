<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RecordResource\Pages;
use App\Filament\Resources\RecordResource\RelationManagers;
use App\Models\Record;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteAction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Gate;


class RecordResource extends Resource
{
    protected static ?string $model = Record::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?int $navigationSort = 6;
     
      public static function getNavigationBadge(): ?string
    {
        // Return number of records
        return (string) Record::count();
    }

      public static function shouldRegisterNavigation(): bool
{
    return Gate::allows('view_any_' . static::getModelLabel());
}



   public static function form(Form $form): Form
{
    return $form
        ->schema([
            Forms\Components\Select::make('patientid')
                ->label('Patient')
                ->relationship('patient', 'name')
                ->searchable()
                ->required(),

            Forms\Components\Select::make('doctorid')
                ->label('Doctors')
                ->options(function () {
                    return \App\Models\User::role('doctor')
                        ->pluck('name', 'id');
                })
                ->searchable()
                ->preload()
                ->multiple() // This allows the selection of multiple doctors
                ->nullable(),

            Forms\Components\Select::make('gpid')
                ->label('GP')
                ->options(function () {
                    return \App\Models\User::role('gp')
                        ->pluck('name', 'id');
                })
                ->searchable()
                ->preload()
                ->multiple() // This allows the selection of multiple GPs
                ->nullable(),

            Forms\Components\Select::make('volunteerid')
                ->label('Volunteer')
                ->options(function () {
                    return \App\Models\User::role('volunteer')
                        ->pluck('name', 'id');
                })
                ->searchable()
                ->preload()
                ->nullable(),

            Forms\Components\Select::make('projectid')
                ->label('Project')
                ->relationship('project', 'name')
                ->searchable(),

            Forms\Components\Select::make('programid')
                ->label('Program')
                ->relationship('program', 'name')
                ->searchable(),

            Forms\Components\TextInput::make('record_type')
                ->label('Record Type'),

            Forms\Components\Textarea::make('notes')
                ->label('Notes'),

            Forms\Components\FileUpload::make('attachments')
                ->label('Attachments')
                ->multiple() // Allows multiple file uploads
                ->directory('records/attachments'),

            Forms\Components\Select::make('status')
                ->options([
                    'draft' => 'Draft',
                    'submitted' => 'Submitted',
                    'reviewed' => 'Reviewed',
                ])
                ->default('draft'),
        ]);
}



    public static function table(Table $table): Table
    {
        return $table
            ->columns([
              Tables\Columns\TextColumn::make('patient.name')
                ->label('Patient Name')
                ->sortable()
                ->searchable(),
              Tables\Columns\TextColumn::make('doctorid')
    ->label('Doctors')
    ->formatStateUsing(function ($state, $record) {
        $ids = $record->doctorid ?? [];

        $ids = is_array($ids) ? $ids : (json_decode($ids, true) ?? []);
        $ids = array_values(array_filter(array_map('intval', $ids)));

        return \App\Models\User::whereIn('id', $ids)->pluck('name')->implode(', ') ?: '-';
    }),

Tables\Columns\TextColumn::make('gpid')
    ->label('GPs')
    ->formatStateUsing(function ($state, $record) {
        $ids = $record->gpid ?? [];

        $ids = is_array($ids) ? $ids : (json_decode($ids, true) ?? []);
        $ids = array_values(array_filter(array_map('intval', $ids)));

        return \App\Models\User::whereIn('id', $ids)->pluck('name')->implode(', ') ?: '-';
    }),

                Tables\Columns\TextColumn::make('volunteer.name')
                ->label('Volunteer Name')
                ->sortable()
                ->searchable(),
                Tables\Columns\TextColumn::make('program.name')
                ->label('Program')
                ->sortable()
                ->searchable(),
                Tables\Columns\TextColumn::make('project.name')
                ->label('Project')
                ->sortable()
                ->searchable(),
                TextColumn::make('status')->sortable(),
                
               
            ])
            ->filters([
                //
            ])
        
            ->actions([
                 EditAction::make()
                    ->modalHeading('Edit Recored')
                    ->modalWidth('lg'),

                DeleteAction::make()
                    ->modalHeading('Delete Recored')
                    ->modalSubheading('Are you sure you want to delete this record?')
                    ->requiresConfirmation(),
        
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
            RelationManagers\CommentsRelationManager::class,
            RelationManagers\PrescriptionsRelationManager::class,
            RelationManagers\CallsRelationManager::class,
            // RelationManagers\DiagnosesRelationManager::class, // Later
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRecords::route('/'),
            'create' => Pages\CreateRecord::route('/create'),
            'edit' => Pages\EditRecord::route('/{record}/edit'),
        ];
    }

    



}
