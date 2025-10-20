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


class PatientResource extends Resource
{
    protected static ?string $model = Patient::class;
    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?int $navigationSort = 5;

    public static function getNavigationBadge(): ?string
    {
        return (string) Patient::count();
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
                TextInput::make('height')->numeric(),
                TextInput::make('weight')->numeric(),
            ]),

            Grid::make(2)->schema([
                ToggleButtons::make('smoke')
                    ->label('Smoking')
                    ->options([1 => 'Yes', 0 => 'No'])
                    ->colors([1 => 'danger', 0 => 'success'])
                    ->inline()
                    ->default(0),

                ToggleButtons::make('drinkalcohol')
                    ->label('Drinks Alcohol')
                    ->options([1 => 'Yes', 0 => 'No'])
                    ->colors([1 => 'danger', 0 => 'success'])
                    ->inline()
                    ->default(0),
            ]),

            Textarea::make('hobbies')->columnSpanFull(),
            Textarea::make('reasonvisit')->columnSpanFull()->label('Reason To Visit'),

            Grid::make(3)->schema([
                TextInput::make('bp')->label('Blood Pressure'),
                TextInput::make('heartrate')->label('Heart Rate'),
                TextInput::make('temperature')->label('Temperature')->numeric(),
            ]),

            Grid::make(2)->schema([
                TextInput::make('occupation'),
                TextInput::make('preferredphysician')->label('Preferred Physician'),
            ]),

            TextInput::make('oxygensaturation')->numeric()->label('Oxygen Saturation'),

            ToggleButtons::make('isactive')
                ->label('Status')
                ->options([1 => 'Active', 0 => 'Inactive'])
                ->colors([1 => 'success', 0 => 'danger'])
                ->inline()
                ->default(1),

            FileUpload::make('profile')->columnSpanFull(),

            Grid::make(2)->schema([
            Textarea::make('existingmedicalcondition')->label('Existing Medical Condition'),
            Textarea::make('existingmedicalhistory')->label('Existing Medical History'),
            ]),
            Grid::make(2)->schema([
            Textarea::make('existingmedication')->label('Existing Medication'),
            Textarea::make('additionalcomment')->label('Additional Comment'),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->sortable()->searchable(),
                TextColumn::make('filenumber')->sortable()->searchable(),
                TextColumn::make('mobile'),
                BadgeColumn::make('isactive')
                    ->label('Status')
                    ->getStateUsing(fn ($record) => $record->isactive ? 'Active' : 'Inactive')
                    ->colors([
                        'success' => fn ($state) => $state === 'Active',
                        'danger' => fn ($state) => $state === 'Inactive',
                    ]),
                TextColumn::make('dob')->date(),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                DeleteBulkAction::make(),
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
