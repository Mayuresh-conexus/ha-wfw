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
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteAction;
use Illuminate\Support\Facades\Gate;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\ViewField;
use Filament\Forms\Set;
use Filament\Forms\Get;
use App\Models\Patient;



class RecordResource extends Resource
{
    protected static ?string $model = Record::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?int $navigationSort = 6;

    public static function getNavigationBadge(): ?string
    {
        return (string) Record::count();
    }

    public static function shouldRegisterNavigation(): bool
    {
        return Gate::allows('view_any_' . static::getModelLabel());
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('patientid')
                ->label('Patient')
                ->relationship('patient', 'name')
                ->searchable()
                ->required()
                ->live()
                ->afterStateUpdated(function (Set $set, Get $get) {
                    self::fillPatientSummary($set, $get);
                }),
 


       Section::make('Patient Summary')
            ->icon('heroicon-o-user-circle')
            ->schema([
                ViewField::make('patient_summary')
                    ->view('filament.forms.patient-summary')
                    ->default([
                        'patient' => null,
                        'generalHealthFiles' => [],
                        'medicationFiles' => [],
                        'malariaFiles' => [],
                        'hivFiles' => [],
                    ])
                    ->afterStateHydrated(function (Set $set, Get $get) {
                        self::fillPatientSummary($set, $get);
                    })
                    ->visible(fn (Get $get) => filled($get('patientid')))
                    ->dehydrated(false),
            ])
    ->visible(fn (Get $get) => filled($get('patientid'))),


            Forms\Components\Select::make('doctorid')
                ->label('Doctors')
                ->options(fn () => \App\Models\User::role('doctor')->pluck('name', 'id'))
                ->searchable()
                ->preload()
                ->multiple()
                ->nullable(),

            Forms\Components\Select::make('gpid')
                ->label('GP')
                ->options(fn () => \App\Models\User::role('gp')->pluck('name', 'id'))
                ->searchable()
                ->preload()
                ->multiple()
                ->nullable(),

            Forms\Components\Select::make('volunteerid')
                ->label('Volunteer')
                ->options(fn () => \App\Models\User::role('volunteer')->pluck('name', 'id'))
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
            
            Forms\Components\Select::make('medicineid')
                ->label('Medicines')
                ->options(fn () => \App\Models\Medicine::pluck('name', 'id'))
                ->searchable()
                ->preload()
                ->multiple()
                ->nullable(),

            Forms\Components\TextInput::make('record_type')
                ->label('Record Type'),

            Forms\Components\Textarea::make('notes')
                ->label('Notes'),

            Forms\Components\FileUpload::make('attachments')
                ->label('Attachments')
                ->disk('public')
                ->directory(fn (callable $get) => 'patients/signature/' . $get('patientid') )
                ->preserveFilenames()
                ->multiple()
                ->reorderable()
                ->appendFiles()
                ->dehydrateStateUsing(function ($state) {
                    // Ensure DB stores JSON array of paths
                    if (blank($state)) {
                        return [];
                    }
           return array_values($state);
    }),

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
            ->defaultSort('created_at', 'desc')
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
            RelationManagers\CallsRelationManager::class,
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


    private static function fillPatientSummary(Set $set, Get $get): void
{
    $patientId = $get('patientid');

    if (! $patientId) {
        $questionSummary = $get('question_summary');
        $symptomIds = $get('symptom_ids');

        // Normalize JSON stored as string
        $questionSummary = is_array($questionSummary) ? $questionSummary : (json_decode($questionSummary ?? '[]', true) ?: []);
        $symptomIds = is_array($symptomIds) ? $symptomIds : (json_decode($symptomIds ?? '[]', true) ?: []);

        $set('patient_summary', [
            'patient' => null,
            'generalHealthFiles' => [],
            'medicationFiles' => [],
            'malariaFiles' => [],
            'hivFiles' => [],
            'question_summary' => $questionSummary,
            'symptom_ids' => $symptomIds,
        ]);
        return;
    }

    $patient = Patient::query()
        ->select([
            'id',
            'name',
            'filenumber',
            'mobile',
            'gender',
            'dob',
            'generalhealth',
            'medication',
            'additionalcomment',
            'profile',
            'generalhealthupload',
            'medicationupload',
            'malariatestupload',
            'hivtestupload',
        ])
        ->find($patientId);

    if (! $patient) {
        $questionSummary = $get('question_summary');
        $symptomIds = $get('symptom_ids');

        $questionSummary = is_array($questionSummary) ? $questionSummary : (json_decode($questionSummary ?? '[]', true) ?: []);
        $symptomIds = is_array($symptomIds) ? $symptomIds : (json_decode($symptomIds ?? '[]', true) ?: []);

        $set('patient_summary', [
            'patient' => null,
            'generalHealthFiles' => [],
            'medicationFiles' => [],
            'malariaFiles' => [],
            'hivFiles' => [],
            'question_summary' => $questionSummary,
            'symptom_ids' => $symptomIds,
        ]);
        return;
    }

    $generalHealthFiles = is_array($patient->generalhealthupload)
        ? $patient->generalhealthupload
        : (json_decode($patient->generalhealthupload ?? '[]', true) ?: []);

    $medicationFiles = is_array($patient->medicationupload)
        ? $patient->medicationupload
        : (json_decode($patient->medicationupload ?? '[]', true) ?: []);

    $malariaFiles = is_array($patient->malariatestupload)
        ? $patient->malariatestupload
        : (json_decode($patient->malariatestupload ?? '[]', true) ?: []);

    $hivFiles = is_array($patient->hivtestupload)
        ? $patient->hivtestupload
        : (json_decode($patient->hivtestupload ?? '[]', true) ?: []);

    $set('patient_summary', [
        'patient' => [
            'id' => $patient->id,
            'name' => $patient->name,
            'filenumber' => $patient->filenumber,
            'mobile' => $patient->mobile,
            'gender' => $patient->gender,
            'dob' => $patient->dob,
            'generalhealth' => $patient->generalhealth,
            'medication' => $patient->medication,
            'additionalcomment' => $patient->additionalcomment,
            'profile' => $patient->profile,
        ],
        'generalHealthFiles' => $generalHealthFiles,
        'medicationFiles' => $medicationFiles,
        'malariaFiles' => $malariaFiles,
        'hivFiles' => $hivFiles,
        'question_summary' => is_array($get('question_summary')) ? $get('question_summary') : (json_decode($get('question_summary') ?? '[]', true) ?: []),
        'symptom_ids' => is_array($get('symptom_ids')) ? $get('symptom_ids') : (json_decode($get('symptom_ids') ?? '[]', true) ?: []),
    ]);
}






}
