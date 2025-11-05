<?php

namespace App\Filament\Resources\RecordResource\RelationManagers;

use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class CallsRelationManager extends RelationManager
{
    protected static string $relationship = 'calls';
    protected static ?string $recordTitleAttribute = 'room_name';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                // Volunteer dropdown (only users with "volunteer" role)
                Forms\Components\Select::make('volunteer_id')
                    ->label('Volunteer')
                    ->options(function () {
                        return User::role('volunteer')->pluck('name', 'id');
                    })
                    ->searchable()
                    ->required(),

                // Doctor dropdown (only users with "doctor" or "gp" role)
                Forms\Components\Select::make('assigned_gp_doctor_id')
                    ->label('GP / Doctor')
                    ->options(function () {
                        return User::role(['doctor', 'gp'])->pluck('name', 'id');
                    })
                    ->searchable()
                    ->required(),

                Forms\Components\DatePicker::make('schedule_date')
                    ->label('Schedule Date')
                    ->required(),

                Forms\Components\TimePicker::make('schedule_start_time')
                    ->label('Start Time')
                    ->required(),

                Forms\Components\TimePicker::make('schedule_end_time')
                    ->label('End Time')
                    ->required(),

                Forms\Components\TextInput::make('room_name')
                    ->label('Room Name')
                    ->required(),

                Forms\Components\Select::make('status')
                    ->options([
                        'scheduled' => 'Scheduled',
                        'completed' => 'Completed',
                        'cancelled' => 'Cancelled',
                    ])
                    ->default('scheduled')
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('volunteer.name')->label('Volunteer')->sortable(),
                Tables\Columns\TextColumn::make('doctor.name')->label('GP / Doctor')->sortable(),
                Tables\Columns\TextColumn::make('schedule_date')->date()->label('Date')->sortable(),
                Tables\Columns\TextColumn::make('schedule_start_time')->label('Start'),
                Tables\Columns\TextColumn::make('schedule_end_time')->label('End'),
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'warning' => 'scheduled',
                        'success' => 'completed',
                        'danger' => 'cancelled',
                    ]),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
