<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ClassSessionResource\Pages;
use App\Filament\Resources\Concerns\RestrictsResourceAccess;
use App\Models\Batch;
use App\Models\ClassSession;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ClassSessionResource extends Resource
{
    use RestrictsResourceAccess;

    protected static ?string $model = ClassSession::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';

    protected static ?string $navigationGroup = 'Learning';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Class Session')
                    ->schema([
                        Forms\Components\Select::make('batch_id')
                            ->label('Batch')
                            ->relationship('batch', 'name')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->afterStateUpdated(function (Forms\Get $get, Forms\Set $set, ?string $state) {
                                $batch = Batch::find($state);
                                if ($batch) {
                                    $set('trainer_id', $batch->trainer_id);
                                    $set('start_time', $batch->start_time?->format('H:i'));
                                    $set('end_time', $batch->end_time?->format('H:i'));
                                    $set('room', $batch->room);
                                }
                            }),
                        Forms\Components\Select::make('trainer_id')
                            ->label('Trainer')
                            ->relationship('trainer', 'name')
                            ->searchable()
                            ->preload(),
                        Forms\Components\DatePicker::make('date')
                            ->required(),
                        Forms\Components\TimePicker::make('start_time')
                            ->seconds(false),
                        Forms\Components\TimePicker::make('end_time')
                            ->seconds(false)
                            ->after('start_time'),
                        Forms\Components\TextInput::make('topic')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('room')
                            ->maxLength(255),
                    ])
                    ->columns(3),
                Forms\Components\Section::make('Notes & Status')
                    ->schema([
                        Forms\Components\Textarea::make('notes')
                            ->rows(3)
                            ->columnSpanFull(),
                        Forms\Components\Select::make('status')
                            ->options([
                                'scheduled' => 'Scheduled',
                                'completed' => 'Completed',
                                'cancelled' => 'Cancelled',
                            ])
                            ->default('scheduled')
                            ->required(),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('start_time')
                    ->label('Time')
                    ->state(fn (ClassSession $record) => $record->start_time?->format('g:i A').' - '.$record->end_time?->format('g:i A')),
                Tables\Columns\TextColumn::make('batch.name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('batch.course.title')
                    ->label('Course')
                    ->toggleable()
                    ->limit(25),
                Tables\Columns\TextColumn::make('topic')
                    ->searchable()
                    ->limit(40),
                Tables\Columns\TextColumn::make('room')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('trainer.name')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'completed' => 'success',
                        'cancelled' => 'danger',
                        default => 'info',
                    }),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('batch_id')
                    ->label('Batch')
                    ->relationship('batch', 'name'),
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'scheduled' => 'Scheduled',
                        'completed' => 'Completed',
                        'cancelled' => 'Cancelled',
                    ]),
                Tables\Filters\SelectFilter::make('date')
                    ->label('When')
                    ->options([
                        'today' => 'Today',
                        'upcoming' => 'Upcoming',
                        'past' => 'Past',
                    ])
                    ->query(fn (Builder $query, array $data) => match ($data['value'] ?? null) {
                        'today' => $query->whereDate('date', today()),
                        'upcoming' => $query->where('date', '>=', today()),
                        'past' => $query->where('date', '<', today()),
                        default => $query,
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
            ])
            ->defaultSort('date');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListClassSessions::route('/'),
            'create' => Pages\CreateClassSession::route('/create'),
            'edit' => Pages\EditClassSession::route('/{record}/edit'),
        ];
    }
}
