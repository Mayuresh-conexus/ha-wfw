<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PatientResource\Pages;
use App\Models\Patient;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Forms\Components\FileUpload;
use Illuminate\Support\Facades\Gate;


class PatientResource extends Resource
{
    protected static ?string $model = Patient::class;
    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?int $navigationSort = 5;

    public static function getNavigationBadge(): ?string
    {
        return (string) Patient::count();
    }

    public static function shouldRegisterNavigation(): bool
    {
        return Gate::allows('view_any_' . static::getModelLabel());
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Grid::make(2)->schema([
                TextInput::make('name')->required()->maxLength(255),
                TextInput::make('filenumber')->required()->unique(ignoreRecord: true),
            ]),

            Grid::make(2)->schema([
                TextInput::make('email')->email(),
                TextInput::make('mobile')->tel(),
            ]),

            Grid::make(2)->schema([
                DatePicker::make('dob')->label('Date of Birth'),
                Select::make('gender')
                    ->options(['Male' => 'Male', 'Female' => 'Female', 'Other' => 'Other'])
                    ->required(),
            ]),


            Grid::make(2)->schema([
                Textarea::make('reasonvisit')->label('Reason To Visit'),
                Textarea::make('familyhealthreason')->label('Family Health Reason'),
            ]),

            Grid::make(2)->schema([
                Textarea::make('generalhealth')->label('General Health'),
                Textarea::make('medication')->label('Medication'),
            ]),

            Grid::make(2)->schema([
               FileUpload::make('generalhealthupload')
                ->label('General Health File')
                ->disk('public')
                ->directory(fn (callable $get) => 'patients/' . $get('filenumber'))
                ->preserveFilenames()
                ->multiple()
                ->reorderable()
                ->acceptedFileTypes(['image/jpeg', 'image/png', 'application/pdf', 'image/jpg'])
                ->maxSize(5120)
                ->appendFiles()
                ->dehydrateStateUsing(function ($state) {
                    // Ensure DB stores JSON array of paths
                    if (blank($state)) {
                        return [];
                    }

        return array_values($state);
    }),
                FileUpload::make('medicationupload')->label('Medication File')
                ->disk('public')
                ->directory(fn (callable $get) => 'patients/' . $get('filenumber'))
                ->preserveFilenames()
                ->multiple()
                ->reorderable()
                ->acceptedFileTypes(['image/jpeg', 'image/png', 'application/pdf', 'image/jpg'])
                ->maxSize(5120)
                ->appendFiles()
                ->dehydrateStateUsing(function ($state) {
                    // Ensure DB stores JSON array of paths
                    if (blank($state)) {
                        return [];
                    }
           return array_values($state);
    }),
            ]),

            Grid::make(2)->schema([
                TextInput::make('occupation'),
                Select::make('preferredphysician')
                    ->options(['Male' => 'Male', 'Female' => 'Female'])
                    ->label('Preferred Physician')
                    ->required(),
            ]),

            Grid::make(2)->schema([
                Textarea::make('malariatest')->label('Malaria Test'),
                Textarea::make('hivtest')->label('HIV Test'),
            ]),

            Grid::make(2)->schema([
                FileUpload::make('malariatestupload')->label('Malaria Test Upload')
                ->disk('public')
                ->directory(fn (callable $get) => 'patients/' . $get('filenumber'))
                ->preserveFilenames()
                ->multiple()
                ->reorderable()
                ->acceptedFileTypes(['image/jpeg', 'image/png', 'application/pdf', 'image/jpg'])
                ->maxSize(5120)
                ->appendFiles()
                ->dehydrateStateUsing(function ($state) {
                    // Ensure DB stores JSON array of paths
                    if (blank($state)) {
                        return [];
                    }
           return array_values($state);
    }),
                FileUpload::make('hivtestupload')->label('HIV Test Upload')
                ->disk('public')
                ->directory(fn (callable $get) => 'patients/' . $get('filenumber'))
                ->preserveFilenames()
                ->multiple()
                ->reorderable()
                ->acceptedFileTypes(['image/jpeg', 'image/png', 'application/pdf', 'image/jpg'])
                ->maxSize(5120)
                ->appendFiles()
                ->dehydrateStateUsing(function ($state) {
                    // Ensure DB stores JSON array of paths
                    if (blank($state)) {
                        return [];
                    }
           return array_values($state);
    }),
            ]),


            Grid::make(4)->schema([
                TextInput::make('bp')->label('Blood Pressure'),
                TextInput::make('heartrate')->label('Heart Rate'),
                TextInput::make('temperature')->label('Temperature')->numeric(),
                TextInput::make('oxygensaturation')->numeric()->label('Oxygen Saturation'),

            ]),

            Grid::make(6)->schema([

                TextInput::make('height')->numeric(),
                ToggleButtons::make('heightunit')
                    ->label('Height Unit')
                    ->options(['CM' => 'CM', 'Inch' => 'Inch'])
                    ->inline(),
                TextInput::make('weight')->numeric(),
                ToggleButtons::make('weightunit')
                    ->options(['KG' => 'KG', 'LBS' => 'LBS'])
                    ->label('Weight Unit')
                    ->inline(),

                ToggleButtons::make('drinkalcohol')
                    ->label('Drinks Alcohol')
                    ->options([1 => 'Yes', 0 => 'No'])
                    ->colors([1 => 'danger', 0 => 'success'])
                    ->inline()
                    ->default(0),
                ToggleButtons::make('smoke')
                    ->label('Smoking')
                    ->options([1 => 'Yes', 0 => 'No'])
                    ->colors([1 => 'danger', 0 => 'success'])
                    ->inline()
                    ->default(0),
            ]),



            Grid::make(2)->schema([
                FileUpload::make('profile')
                    ->label('Profile Picture')
                    ->image()
                    ->maxSize(5120),
                Textarea::make('additionalcomment')->label('Additional Comment')->rows(3),
            ]),

            ToggleButtons::make('is_active')
                ->label('Status')
                ->options([1 => 'Active', 0 => 'Inactive'])
                ->colors([1 => 'success', 0 => 'danger'])
                ->inline()
                ->default(1),


        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->sortable()->searchable(),
                TextColumn::make('filenumber')->sortable()->searchable(),
                TextColumn::make('mobile')->visibleFrom('md'),
                BadgeColumn::make('is_active')
                    ->label('Status')
                    ->getStateUsing(fn($record) => $record->is_active ? 'Active' : 'Inactive')
                    ->colors([
                        'success' => fn($state) => $state === 'Active',
                        'danger' => fn($state) => $state === 'Inactive',
                    ]),
                TextColumn::make('dob')->date()->visibleFrom('lg'),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([])
            ->headerActions([
                Tables\Actions\ExportAction::make()
                    ->exporter(\App\Filament\Exports\PatientExporter::class),
            ])
            ->actions([
                Tables\Actions\EditAction::make()->icon('heroicon-o-pencil'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    DeleteBulkAction::make()->icon('heroicon-o-trash'),
                    Tables\Actions\ExportBulkAction::make()
                        ->exporter(\App\Filament\Exports\PatientExporter::class)
                        ->icon('heroicon-o-arrow-down-tray'),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            PatientResource\RelationManagers\RecordsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPatients::route('/'),
            'create' => Pages\CreatePatient::route('/create'),
            'edit' => Pages\EditPatient::route('/{record}/edit'),
        ];
    }
}
