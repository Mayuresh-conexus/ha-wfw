<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SpecialityResource\Pages;
use App\Models\Speciality;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BooleanColumn;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\EditAction;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Filament\Tables\Columns\BadgeColumn;


class SpecialityResource extends Resource
{

    protected static ?string $model = Speciality::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';
    protected static ?int $navigationSort = 0;
     
    public static function getNavigationBadge(): ?string
    {
        return (string) Speciality::count();
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
        ->label('Speciality Name')
        ->required()
        ->maxLength(255),

    \Filament\Forms\Components\ToggleButtons::make('is_active')
        ->label('Status')
        ->options([
            true => 'Active',
            false => 'Inactive',
        ])
        ->colors([
            true => 'success',
            false => 'danger',
        ])
        ->inline() // horizontal pill buttons
        ->default(true),
]);




    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->sortable(),
                TextColumn::make('name')->sortable()->searchable(),
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
                    ->query(fn ($query) => $query->where('isactive', true))
                    ->label('Active Specialities'),
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('New Speciality')
                    ->modalHeading('Create Speciality')
                    ->modalWidth('lg')
                    ->createAnother(true), // ✅ allows "Save & create another"
            ])
            ->actions([
                EditAction::make()
                    ->modalHeading('Edit Speciality')
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
        'index' => Pages\ListSpecialities::route('/'),
        // 'create' => Pages\CreateSpeciality::route('/create'),  ❌ remove this
        // 'edit' => Pages\EditSpeciality::route('/{record}/edit'), ❌ remove this
    ];
}

}
