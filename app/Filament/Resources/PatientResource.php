<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PatientResource\Pages;
use App\Models\Patient;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PatientResource extends Resource
{
    protected static ?string $model = Patient::class;
    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?int $navigationSort = 5;
     
      public static function getNavigationBadge(): ?string
    {
        // Return number of records
        return (string) Patient::count();
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('name')->required()->maxLength(255),
            Forms\Components\TextInput::make('filenumber')->required()->unique(ignoreRecord: true),
            Forms\Components\TextInput::make('email')->email(),
            Forms\Components\TextInput::make('mobile')->tel(),
            Forms\Components\DatePicker::make('dob'),
            Forms\Components\TextInput::make('height')->numeric(),
            Forms\Components\TextInput::make('weight')->numeric(),
            Forms\Components\Toggle::make('smoke')->default(false),
            Forms\Components\Toggle::make('drinkalcohol')->default(false),
            Forms\Components\Textarea::make('hobbies'),
            Forms\Components\Textarea::make('reasonvisit'),
            Forms\Components\TextInput::make('bp'),
            Forms\Components\TextInput::make('heartrate'),
            Forms\Components\TextInput::make('occupation'),
            Forms\Components\Select::make('gender')->options(['Male' => 'Male', 'Female' => 'Female', 'Other' => 'Other']),
            Forms\Components\Toggle::make('isactive')->default(true),
            Forms\Components\FileUpload::make('profile'),
            Forms\Components\Textarea::make('existingmedicalcondition'),
            Forms\Components\Textarea::make('existingmedicalhistory'),
            Forms\Components\Textarea::make('existingmedication'),
            Forms\Components\TextInput::make('preferredphysician'),
            Forms\Components\TextInput::make('oxygensaturation')->numeric(),
            Forms\Components\TextInput::make('temperature')->numeric(),
            Forms\Components\Textarea::make('additionalcomment'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('filenumber')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('mobile'),
                Tables\Columns\IconColumn::make('isactive')->boolean(),
                Tables\Columns\TextColumn::make('dob')->date(),
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
