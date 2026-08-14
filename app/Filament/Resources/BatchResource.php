<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BatchResource\Pages;
use App\Filament\Resources\Concerns\AllowsTrainerAccess;
use App\Models\Batch;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class BatchResource extends Resource
{
    use AllowsTrainerAccess;

    protected static ?string $model = Batch::class;

    protected static ?string $navigationIcon = 'heroicon-o-inbox-stack';

    protected static ?string $navigationGroup = 'Academics';

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        $user = auth()->user();
        if ($user && $user->isTrainer() && $user->trainer) {
            $query->where('trainer_id', $user->trainer->id);
        }

        return $query;
    }

    public static function form(Form $form): Form
    {
        $user = auth()->user();

        return $form
            ->schema([
                Forms\Components\Section::make('Batch Information')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('e.g. LD-15'),
                        Forms\Components\Select::make('course_id')
                            ->label('Course')
                            ->relationship('course', 'title')
                            ->searchable()
                            ->preload()
                            ->required(),
                        Forms\Components\Select::make('trainer_id')
                            ->label('Trainer')
                            ->relationship('trainer', 'name')
                            ->searchable()
                            ->preload()
                            ->default($user?->isTrainer() ? $user->trainer?->id : null),
                    ])
                    ->columns(3),
                Forms\Components\Section::make('Schedule')
                    ->schema([
                        Forms\Components\DatePicker::make('start_date')
                            ->required(),
                        Forms\Components\DatePicker::make('end_date')
                            ->required()
                            ->after('start_date'),
                        Forms\Components\Select::make('class_days')
                            ->multiple()
                            ->options([
                                'Sat' => 'Saturday',
                                'Sun' => 'Sunday',
                                'Mon' => 'Monday',
                                'Tue' => 'Tuesday',
                                'Wed' => 'Wednesday',
                                'Thu' => 'Thursday',
                                'Fri' => 'Friday',
                            ]),
                        Forms\Components\TimePicker::make('start_time')
                            ->seconds(false),
                        Forms\Components\TimePicker::make('end_time')
                            ->seconds(false)
                            ->after('start_time'),
                    ])
                    ->columns(3),
                Forms\Components\Section::make('Venue & Capacity')
                    ->schema([
                        Forms\Components\TextInput::make('room')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('max_students')
                            ->numeric()
                            ->minValue(1)
                            ->maxValue(1000)
                            ->default(30)
                            ->required(),
                        Forms\Components\Select::make('status')
                            ->options([
                                'active' => 'Active',
                                'upcoming' => 'Upcoming',
                                'completed' => 'Completed',
                                'inactive' => 'Inactive',
                            ])
                            ->default('active')
                            ->required(),
                    ])
                    ->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('course.title')
                    ->searchable()
                    ->sortable()
                    ->limit(30),
                Tables\Columns\TextColumn::make('trainer.name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('start_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('end_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('start_time')
                    ->label('Time')
                    ->state(fn (Batch $record) => $record->start_time?->format('g:i A') . ' - ' . $record->end_time?->format('g:i A')),
                Tables\Columns\TextColumn::make('enrolled_count')
                    ->label('Seats')
                    ->badge()
                    ->color(fn (Batch $record): string => $record->capacity_reached ? 'danger' : 'gray')
                    ->getStateUsing(fn (Batch $record): string => "{$record->enrolled_count}/{$record->max_students}"),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'active' => 'success',
                        'upcoming' => 'info',
                        'completed' => 'gray',
                        default => 'danger',
                    }),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('course_id')
                    ->label('Course')
                    ->relationship('course', 'title'),
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'active' => 'Active',
                        'upcoming' => 'Upcoming',
                        'completed' => 'Completed',
                    ]),
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBatches::route('/'),
            'create' => Pages\CreateBatch::route('/create'),
            'edit' => Pages\EditBatch::route('/{record}/edit'),
        ];
    }
}