<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BodySectionResource\Pages;
use App\Models\BodySection;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class BodySectionResource extends Resource
{
    protected static ?string $model = BodySection::class;

    protected static ?string $navigationIcon = 'heroicon-o-user';

    protected static ?int $navigationSort = 8;
     
      public static function getNavigationBadge(): ?string
    {
        // Return number of records
        return (string) BodySection::count();
    }


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('tag')
                    ->label('Tag')
                    ->maxLength(255)
                    ->nullable(),

                Forms\Components\Toggle::make('isactive')
                    ->label('Active')
                    ->default(true),

                Forms\Components\Toggle::make('iscritical')
                    ->label('Critical')
                    ->default(false),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->sortable(),
                Tables\Columns\TextColumn::make('name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('tag'),
                Tables\Columns\IconColumn::make('isactive')->boolean()->label('Active'),
                Tables\Columns\IconColumn::make('iscritical')->boolean()->label('Critical'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('d M Y H:i'),
            ])
            ->filters([])
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
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListBodySections::route('/'),
            'create' => Pages\CreateBodySection::route('/create'),
            'edit'   => Pages\EditBodySection::route('/{record}/edit'),
        ];
    }
}
