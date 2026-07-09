<?php

namespace App\Filament\Resources\RecordResource\RelationManagers;

use App\Models\RecordComment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class CommentsRelationManager extends RelationManager
{
    protected static string $relationship = 'comments';

    protected static ?string $title = 'Comment';

    public function mount(): void
    {
        parent::mount();

        $this->seedIntakeComment();
    }

    /**
     * The volunteer-entered intake note (patients.additionalcomment) has no
     * thread of its own — it's folded in here as the thread's first entry,
     * attributed to the record's volunteer, so reviewers see one unified
     * comment section instead of a separate read-only block elsewhere on the
     * page. Seeded once (guarded by "no comments yet") so it isn't duplicated
     * on every page load.
     */
    protected function seedIntakeComment(): void
    {
        $record = $this->getOwnerRecord();
        $comment = trim((string) ($record->patient?->additionalcomment ?? ''));

        // userid is required — without a volunteer on the record there's no
        // one to attribute the note to, so leave it out of the thread.
        if ($comment === '' || blank($record->volunteerid)) {
            return;
        }

        // Guard against duplicates by matching the exact intake text, not
        // "any comment exists" — otherwise records that already had a staff
        // comment before this seeding existed would never get the intake
        // note added at all.
        if ($record->comments()->where('description', $comment)->exists()) {
            return;
        }

        // Eloquent's create() always stamps created_at/updated_at with now()
        // on save, ignoring passed-in values — disable timestamps for this
        // one insert so the note keeps the patient's original intake date
        // and sorts to the front of the thread.
        $intake = new RecordComment([
            'recordid' => $record->id,
            'userid' => $record->volunteerid,
            'description' => $comment,
        ]);
        $intake->timestamps = false;
        $intake->created_at = $record->patient->created_at;
        $intake->updated_at = $record->patient->created_at;
        $intake->save();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Textarea::make('description')
                    ->label('Comment')
                    ->required()
                    ->maxLength(500),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('description')
            ->defaultSort('created_at', 'asc')
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Added By')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('description')
                    ->label('Comment')
                    ->wrap(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Date')
                    ->dateTime('d M Y, h:i A')
                    ->sortable(),
            ])
            ->filters([])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Add Comment')
                    // Automatically assign logged-in user
                    ->mutateFormDataUsing(function (array $data): array {
                        $data['userid'] = Auth::id();
                        return $data;
                    }),
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
