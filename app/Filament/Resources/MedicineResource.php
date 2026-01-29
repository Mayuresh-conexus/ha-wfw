<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MedicineResource\Pages;
use App\Filament\Resources\MedicineResource\RelationManagers;
use App\Models\Medicine;
use App\Models\Symptom;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Gate;

class MedicineResource extends Resource
{
     protected static ?string $model = Medicine::class;
    protected static ?string $navigationIcon = 'heroicon-o-plus';
    protected static ?int $navigationSort = 10;

    public static function getNavigationBadge(): ?string
    {
        return (string) Medicine::count();
    }

    //  public static function shouldRegisterNavigation(): bool
    // {
    //     return Gate::allows('view_any_' . static::getModelLabel());
    // }

    public static function form(Form $form): Form
    {
        return $form->schema([
        Forms\Components\Select::make('symptom_ids')
    ->label('Symptoms')
    ->options(Symptom::pluck('name', 'id'))
    ->multiple()
    ->searchable()
    ->required(),


        Forms\Components\TextInput::make('name')
            ->required(),

        Forms\Components\Select::make('type')
            ->options([
                'tablet' => 'Tablet',
                'syrup' => 'Syrup',
                'capsule' => 'Capsule',
            ]),

        Forms\Components\TextInput::make('dosage'),

        Forms\Components\Toggle::make('is_active')
            ->default(true),
    ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
        

        Tables\Columns\TextColumn::make('name')
            ->searchable(),

        Tables\Columns\TextColumn::make('type'),

        Tables\Columns\TextColumn::make('dosage'),

        Tables\Columns\IconColumn::make('is_active')
            ->boolean(),
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
            'index' => Pages\ListMedicines::route('/'),
            // 'create' => Pages\CreateMedicine::route('/create'),
            // 'edit' => Pages\EditMedicine::route('/{record}/edit'),
        ];
    }
}
