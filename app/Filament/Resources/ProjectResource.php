<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProjectResource\Pages;
use App\Models\Project;
use App\Models\City;
use App\Models\State;
use App\Models\Country;
use App\Models\Program;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput\Mask;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BooleanColumn;

class ProjectResource extends Resource
{
    protected static ?string $model = Project::class;

    protected static ?string $navigationIcon = 'heroicon-o-folder';
    protected static ?int $navigationSort = 3;
     
      public static function getNavigationBadge(): ?string
    {
        // Return number of records
        return (string) Project::count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label('Project Name')
                    ->required()
                    ->maxLength(255),
                Textarea::make('description')
                    ->label('Description')
                    ->nullable(),
                Select::make('cityid')
                    ->label('City')
                    ->relationship('city', 'name')
                    ->required(),
                Select::make('stateid')
                    ->label('State')
                    ->relationship('state', 'name')
                    ->required(),
                Select::make('countryid')
                    ->label('Country')
                    ->relationship('country', 'name')
                    ->required(),
                Select::make('programid')
                    ->label('Program')
                    ->relationship('program', 'name')
                    ->nullable(),
                TextInput::make('othercity')
                    ->label('Other City')
                    ->nullable()
                    ->maxLength(255),
                Toggle::make('isactive')
                    ->label('Is Active')
                    ->default(true),
                DatePicker::make('startdate')
                    ->label('Start Date')
                    ->nullable(),
                DatePicker::make('enddate')
                    ->label('End Date')
                    ->nullable(),
                TextInput::make('budget')
                    ->label('Budget')
                    ->numeric()
                    ->nullable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->sortable(),
                TextColumn::make('name')->sortable()->searchable(),
                TextColumn::make('city.name')->label('City'),
                TextColumn::make('state.name')->label('State'),
                TextColumn::make('country.name')->label('Country'),
                TextColumn::make('program.name')->label('Program')->nullable(),
                BooleanColumn::make('isactive')->label('Active')->sortable(),
                TextColumn::make('startdate')->date()->label('Start Date'),
                TextColumn::make('enddate')->date()->label('End Date'),
                TextColumn::make('budget')->money('USD', true)->label('Budget'),
                TextColumn::make('created_at')->dateTime()->label('Created'),
            ])
            ->filters([
                Tables\Filters\Filter::make('active')
                    ->query(fn ($query) => $query->where('isactive', true))
                    ->label('Active Projects'),
            ])
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProjects::route('/'),
            'create' => Pages\CreateProject::route('/create'),
            'edit' => Pages\EditProject::route('/{record}/edit'),
        ];
    }
}
