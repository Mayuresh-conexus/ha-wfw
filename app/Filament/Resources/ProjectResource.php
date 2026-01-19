<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProjectResource\Pages;
use App\Models\Project;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteAction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Gate;

class ProjectResource extends Resource
{
    protected static ?string $model = Project::class;
    protected static ?string $navigationIcon = 'heroicon-o-folder';
    protected static ?int $navigationSort = 3;

    public static function getNavigationBadge(): ?string
    {
        return (string) Project::count();
    }

      public static function shouldRegisterNavigation(): bool
{
    return Gate::allows('view_any_' . static::getModelLabel());
}

    // Optimize table queries with eager loading
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with(['city', 'state', 'country', 'program']);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make(2)->schema([
                    TextInput::make('name')
                        ->label('Project Name')
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

                Forms\Components\Select::make('gpid')
               ->label('Doctors / GP')
                ->options(fn () =>
                    User::role(['doctor', 'gp'])
                        ->pluck('name', 'id')
                )
                ->multiple()
                ->searchable()
                ->preload()
                ->nullable()
                ->afterStateHydrated(function ($component, $state, $record) {
                    if ($record && $record->gpid) {
                        $component->state($record->gpid);
                    }
                })
                ->saveRelationshipsUsing(function ($record, $state) {
                    $record->gpid = $state;
                    $record->save();
                }),

            Forms\Components\Select::make('volunteerid')
                ->label('Volunteer')
                ->options(function () {
                    return \App\Models\User::role('volunteer')
                        ->pluck('name', 'id');
                })
                ->searchable()
                ->preload()
                ->nullable(),

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
                
                Select::make('programid')
                    ->label('Program')
                    ->relationship('program', 'name')
                    ->searchable()
                    ->nullable(),

                Textarea::make('description')
                    ->label('Description')
                    ->nullable()
                    ->columnSpanFull(),

                // Use preload for small tables or searchable for large tables
                       Select::make('countryid')
                        ->label('Country')
                        ->relationship('country', 'name')
                        ->reactive()
                        ->afterStateUpdated(fn ($state, callable $set) => $set('stateid', null))
                        ->required(),

                Select::make('stateid')
                        ->label('State')
                        ->reactive()
                        ->afterStateUpdated(fn ($state, callable $set) => $set('cityid', null))
                        ->options(fn (callable $get) => 
                            $get('countryid') ? \App\Models\State::where('countryid', $get('countryid'))->pluck('name', 'id') : []
                        )
                        ->required(),

                Select::make('cityid')
                        ->label('City')
                        ->reactive()
                        ->options(fn (callable $get) =>
                            $get('stateid') ? \App\Models\City::where('stateid', $get('stateid'))->pluck('name', 'id') : []
                        )
                        ->required(),

                TextInput::make('othercity')
                    ->label('Other City')
                    ->nullable()
                    ->maxLength(255),


               
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->sortable(),
                TextColumn::make('name')->sortable()->searchable(),
                TextColumn::make('city.name')->label('City')->default('—')->toggleable(),
                TextColumn::make('state.name')->label('State')->default('—')->toggleable(),
                TextColumn::make('country.name')->label('Country')->default('—')->toggleable(),
                TextColumn::make('program.name')->label('Program')->limit(50)->default('—')->toggleable(),
                TextColumn::make('startdate')->date()->label('Start Date')->toggleable(),
                TextColumn::make('enddate')->date()->label('End Date')->toggleable(),
                TextColumn::make('budget')->money('USD', true)->label('Budget')->toggleable(),
                TextColumn::make('created_at')->dateTime()->label('Created')->toggleable(),
                BadgeColumn::make('is_active')
                    ->label('Status')
                    ->getStateUsing(fn ($record) => $record->is_active ? 'Active' : 'Inactive')
                    ->colors([
                        'success' => fn ($state) => $state === 'Active',
                        'danger' => fn ($state) => $state === 'Inactive',
                    ])->toggleable(),
            ])
            ->filters([
                Tables\Filters\Filter::make('active')
                    ->query(fn ($query) => $query->where('is_active', true))
                    ->label('Active Projects'),
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('New Project')
                    ->modalHeading('Create Project')
                    ->modalWidth('lg')
                    ->createAnother(true),
            ])
            ->actions([
                EditAction::make()
                    ->modalHeading('Edit Project')
                    ->modalWidth('lg'),

                DeleteAction::make()
                    ->modalHeading('Delete Project')
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
            'index' => Pages\ListProjects::route('/'),
        ];
    }
}
