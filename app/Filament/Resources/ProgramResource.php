<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProgramResource\Pages;
use App\Models\Program;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\ToggleButtons;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\EditAction;
use Illuminate\Support\Facades\Gate;

class ProgramResource extends Resource
{
    protected static ?string $model = Program::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-plus';
    protected static ?int $navigationSort = 1;
     
    public static function getNavigationBadge(): ?string
    {
        return (string) Program::count();
    }

     public static function shouldRegisterNavigation(): bool
{
    return Gate::allows('view_any_' . static::getModelLabel());
}

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make(2)->schema([
                    TextInput::make('name')
                        ->label('Program Name')
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

                Textarea::make('description')
                    ->label('Description')
                    ->nullable()
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->sortable(),
                TextColumn::make('name')->sortable()->searchable(),
                TextColumn::make('description')
                    ->limit(50)
                    ->formatStateUsing(fn ($state) => $state ?? '—')->toggleable(),

                BadgeColumn::make('is_active')
                    ->label('Status')
                    ->getStateUsing(fn ($record) => $record->is_active == 1 ? 'Active' : 'Inactive')
                    ->colors([
                        'success' => fn ($state) => $state === 'Active',
                        'danger' => fn ($state) => $state === 'Inactive',
                    ]),

                TextColumn::make('created_at')->dateTime()->label('Created')->toggleable(),
                TextColumn::make('updated_at')->dateTime()->label('Updated')->toggleable(),
            ])
            ->filters([
                Tables\Filters\Filter::make('active')
                    ->query(fn ($query) => $query->where('is_active', true))
                    ->label('Active Programs'),
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('New Program')
                    ->modalHeading('Create Program')
                    ->modalWidth('lg')
                    ->createAnother(true),
            ])
            ->actions([
                EditAction::make()
                    ->modalHeading('Edit Program')
                    ->modalWidth('lg'),
                 Tables\Actions\DeleteAction::make()
                    ->modalHeading('Delete Speciality') // optional
                    ->modalSubheading('Are you sure you want to delete this record?') // optional
                    ->requiresConfirmation(), // default is true
                        
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
            'index' => Pages\ListPrograms::route('/'),
            // remove create/edit pages since we’re using modals
        ];
    }
}
