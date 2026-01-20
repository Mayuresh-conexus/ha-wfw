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
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Grid;
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

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([

            Grid::make(4)->schema([
            Select::make('gender')
                ->label('Gender / Category')
                ->options([
                    'Male' => 'Male',
                    'Female' => 'Female',
                    'Child' => 'Child',
                ])
                ->nullable()
                ->searchable(),

            Select::make('symptomid')
                ->label('Symptom')
                ->relationship('symptom', 'name')
                ->searchable()
                ->live(),

            TextInput::make('question_index')
                ->label('Question Index')
                ->required()
                ->maxLength(255),

            TextInput::make('tags')
                ->label('Tags')
                ->nullable()
                ->maxLength(255),
        ]),


                // Main question text
                RichEditor::make('question_text')
                ->label('Question')
                ->required()
                ->columnSpanFull(),

           
                
        
                    

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
    ->options(function (\Filament\Forms\Get $get) {
        $symptomId = $get('../../symptomid');

        $options = \App\Models\Question::query()
            ->when($symptomId, fn ($q) => $q->where('symptomid', $symptomId))
            ->orderBy('id')
            ->pluck('question_text', 'id')
            ->toArray();

        return ['none' => 'None (End Flow)'] + $options;
    })
    ->default('none')
    ->nullable()
    ->dehydrateStateUsing(function ($state) {
        return $state === 'none' ? false : (int) $state;
    })
    ->searchable()
    ->live()
    ->helperText('Select the next question for this answer, or choose "None (End Flow)".'),
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
                TextColumn::make('symptom_question')
                    ->label('Symptom')
                    ->getStateUsing(function ($record) {
                        return $record->symptom->name . ' : Q index ' . $record->question_index;
                    })
                    ->sortable()
                    ->searchable(),
                
                TextColumn::make('gender')->toggleable(),
                 
                BadgeColumn::make('is_active')
                    ->label('Status')
                    ->getStateUsing(fn ($record) => $record->is_active ? 'Active' : 'Inactive')
                    ->colors([
                        'success' => fn ($state) => $state === 'Active',
                        'danger' => fn ($state) => $state === 'Inactive',
                    ]),
                TextColumn::make('created_at')->dateTime('d M Y H:i')->label('Created')->toggleable(),
                
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
