<?php

namespace App\Filament\Resources;

use App\Filament\Resources\QuestionResource\Pages;
use App\Models\Question;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\ToggleButtons;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteAction;
use Illuminate\Support\Facades\Gate;

class QuestionResource extends Resource
{
    protected static ?string $model = Question::class;
    protected static ?string $navigationIcon = 'heroicon-o-question-mark-circle';
    protected static ?int $navigationSort = 9;

    public static function getNavigationBadge(): ?string
    {
        return (string) Question::count();
    }
      public static function shouldRegisterNavigation(): bool
{
    return Gate::allows('view_any_' . static::getModelLabel());
}

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
               
                    TextInput::make('name')
                        ->label('Question')
                        ->required()
                        ->maxLength(255)
                        ->columnSpanFull(),

                    ToggleButtons::make('is_active')
                        ->label('Status')
                        ->options([1 => 'Active', 0 => 'Inactive'])
                        ->colors([1 => 'success', 0 => 'danger'])
                        ->inline()
                        ->default(1),

                TextInput::make('questiontype')
                    ->label('Question Type')
                    ->nullable()
                    ->maxLength(255)
                    ->columnSpanFull(),

                TextInput::make('tags')
                    ->label('Tags')
                    ->nullable()
                    ->maxLength(255)
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->sortable(),
                TextColumn::make('name')->sortable()->searchable(),
                TextColumn::make('questiontype')->label('Question Type')->sortable(),
                TextColumn::make('tags')->sortable(),

                BadgeColumn::make('is_active')
                    ->label('Status')
                    ->getStateUsing(fn($record) => $record->is_active ? 'Active' : 'Inactive')
                    ->colors([
                        'success' => fn($state) => $state === 'Active',
                        'danger' => fn($state) => $state === 'Inactive',
                    ]),

                TextColumn::make('created_at')->dateTime('d M Y H:i')->label('Created'),
            ])
            ->filters([])
            ->headerActions([
                CreateAction::make()
                    ->label('New Question')
                    ->modalHeading('Create Question')
                    ->modalWidth('lg')
                    ->createAnother(true),
            ])
            ->actions([
                EditAction::make()
                    ->modalHeading('Edit Question')
                    ->modalWidth('lg'),

                DeleteAction::make()
                    ->modalHeading('Delete Question')
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
            'index' => Pages\ListQuestions::route('/'),
            // modal handles create/edit
        ];
    }
}
