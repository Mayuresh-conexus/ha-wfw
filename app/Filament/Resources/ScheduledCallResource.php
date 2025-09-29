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

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
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
