<?php

namespace App\Filament\Resources;

use App\Filament\Resources\QuestionResource\Pages;
use App\Models\Question;
use App\Models\QuestionSet; // Import QuestionSet if you want to link questions to a set
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Components\Repeater; // Import Repeater
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteAction;

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
        return true;
    }

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                // Symptom or category (optional)
                TextInput::make('symptoms')
                    ->label('Symptom or Category')
                    ->nullable(),

                // Main question text
                TextInput::make('question_text')
                    ->label('Question')
                    ->required()
                    ->maxLength(255),
                
                TextInput::make('question_index')
                    ->label('Question Index')
                    ->required()
                    ->maxLength(255),

                // Tags to categorize the question
                TextInput::make('tags')
                    ->label('Tags')
                    ->nullable()
                    ->maxLength(255),

                    

                // Repeater for adding multiple answers with next question selection
                Repeater::make('answers') // Answers Repeater
                    ->label('Answers')
                    ->schema([
                        TextInput::make('answer')
                            ->label('Answer Option')
                            ->required(),

                        // Select next question for this answer
                        Select::make('next_question_id')
            ->label('Next Question')
            ->options(Question::all()->pluck('question_text', 'id')->prepend('None (End Flow)', 'false')) // Prepend 'None' option with 'false' value
            ->nullable() // Allow null values
            ->default('false') // Set 'false' as the default value
            ->helperText('Select the next question to display after this answer or select "None (End Flow)" to stop the flow')
    ])
                    ->required()
                    ->columnSpanFull()
                    ->defaultItems(1), // You can define the number of initial items here

                // Active status for the question
                ToggleButtons::make('is_active')
                    ->label('Status')
                    ->options([1 => 'Active', 0 => 'Inactive'])
                    ->inline()
                    ->default(1),

                
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->sortable(),
                TextColumn::make('question_text')->sortable()->searchable(),
                BadgeColumn::make('is_active')->label('Status'),
                TextColumn::make('created_at')->dateTime('d M Y H:i')->label('Created'),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->headerActions([
                CreateAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListQuestions::route('/'),
            'create' => Pages\CreateQuestion::route('/create'),
            'edit' => Pages\EditQuestion::route('/{record}/edit'),
        ];
    }
}
