<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ScheduledCallResource\Pages;
use App\Filament\Resources\ScheduledCallResource\RelationManagers;
use App\Models\ScheduledCall;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Gate;
use App\Models\User;

class ScheduledCallResource extends Resource
{
    protected static ?string $model = ScheduledCall::class;

    protected static ?string $navigationIcon = 'heroicon-o-video-camera';

    protected static ?int $navigationSort = 11;
     
      public static function getNavigationBadge(): ?string
    {
        // Return number of records
        return (string) ScheduledCall::count();
    }

  public static function shouldRegisterNavigation(): bool
    {
        return Gate::allows('view_any_' . static::getModelLabel());
    }


    public static function form(Form $form): Form
    {
        return $form
             ->schema([
                // Patient dropdown
                Forms\Components\Select::make('patientid')
                ->label('Patient')
                ->options(\App\Models\Patient::pluck('name', 'id'))
                ->searchable()
                ->required(),

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

    public static function table(Table $table): Table
    {
        return $table
           ->columns([
                Tables\Columns\TextColumn::make('patient.name')
    ->label('Patient')
    ->sortable()
    ->searchable(),

                Tables\Columns\TextColumn::make('volunteer.name')->label('Volunteer')->sortable(),
                Tables\Columns\TextColumn::make('doctor.name')->label('GP / Doctor')->sortable(),
                Tables\Columns\TextColumn::make('schedule_date')->date()->label('Date')->sortable(),
                Tables\Columns\TextColumn::make('schedule_start_time')->label('Start'),
                Tables\Columns\TextColumn::make('schedule_end_time')->label('End'),
                Tables\Columns\TextColumn::make('zoom_join_url')
                    ->label('Zoom')
                    ->formatStateUsing(fn ($state) => $state ? 'Join' : '-')
                    ->url(fn ($record) => $record->zoom_join_url, true)
                    ->openUrlInNewTab(),

                Tables\Columns\TextColumn::make('zoom_start_url')
                    ->label('Host')
                    ->formatStateUsing(fn ($state) => $state ? 'Start' : '-')
                    ->url(fn ($record) => $record->zoom_start_url, true)
                    ->openUrlInNewTab()
                    ->visible(fn () => auth()->user()?->hasRole('admin')),

                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'warning' => 'scheduled',
                        'success' => 'completed',
                        'danger' => 'cancelled',
                    ]),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
            'index' => Pages\ListScheduledCalls::route('/'),
            'create' => Pages\CreateScheduledCall::route('/create'),
            'edit' => Pages\EditScheduledCall::route('/{record}/edit'),
        ];
    }
}
